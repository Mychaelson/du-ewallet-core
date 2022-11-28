<?php

namespace App\Macros\Accounts;

use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Accounts\PasswordThrottlesRepository;
use App\Repositories\Accounts\PasswordWrongHistoriesRepository;

use App\Notifications\Accounts\PinLockedNotification;

class ValidatePasswordMacro
{
	function __construct(
		private UsersRepository $usersRepository, 
        private PasswordThrottlesRepository $ptRepository,
        private PasswordWrongHistoriesRepository $pwhRepository,
        private PinLockedNotification $plNotification
	)
	{}

	public function handle($data)
	{
        $user = $data['user'];

        //should return error if lock is happening
        if ($user->status == 2) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 401;
            $data['response']['message'] = trans('messages.user-suspended');
            return $data;
        }

        //should check password hash
        $validatePassword = $this->usersRepository->validatePasswordHash($data['request']['content']['password'], $user->password);
        if ($validatePassword) {
            //should release any lock and release success
            $this->ptRepository->reset($user->id);
            $data['response']['message'] = trans('messages.pin-confirmed');
            $data['response']['data'] = array(
                'confirm' => true,
                'confirm_code'=> generate_confirm_code($user->id),
            );

        } else {
            //should check password throttle
            $throttle = $this->ptRepository->getThrottleByUserId($user->id);
            if (is_null($throttle)) {
                //new user should create one
                $throttle = $this->ptRepository->create([
                    'user_id' => $user->id,
                    'request_count' => 1,
                    'lock' => 0,
                    'ip' => $data['ip'],
                ]);
            } else {
                //increment the request count
                $this->ptRepository->increment($user->id);
                $throttle->request_count = $throttle->request_count + 1;
            }

            //should get the current token
            $token = $this->usersRepository->getAccessTokens($user);

            //check tries limit and log failed
            $life = config('throttle.limit.tries') - $throttle->request_count;
            $message = trans('messages.pin-confirmation-failed', ['chance' => $life]);
            $this->pwhRepository->create([
                'user_id' => $user->id,
                'ip' => $data['ip'],
                'location' => $data['location'],
                'device' => $token->device,
                'device_id' => $token->device_id,
                'message' => $message
            ]);

            $data['response']['success'] = false;
            $data['response']['response_code'] = 403;
            $data['response']['message'] = $message;

            if ($life > 0) {
                //still safe, return response
                $data['response']['data'] = array(
                    'confirm' => false,
                    'error_code' => 3,
                    'message' => $message,
                );
            } else {
                //should suspend user
                //$this->usersRepository->update(['status' => 2], $user->id);

                //should lock password throttle
                $penaltyTime = $this->ptRepository->lock($user->id, $data['ip']);
                $penaltyDuration = round((strtotime($penaltyTime) - time())/60);

                //should insert user blocked into password wrong history
                $message = trans('messages.pin-confirmation-penalty', ['penalty' => $penaltyDuration]);
                $this->pwhRepository->create([
                    'user_id' => $user->id,
                    'ip' => $data['ip'],
                    'location' => $data['location'],
                    'device' => $token->device,
                    'device_id' => $token->device_id,
                    'message' => $message
                ]);

                //should revoke tokens
                //$this->usersRepository->revokeTokens($data['user'], $token->id);

                //should notify user
                if (!is_null($user->email) && $user->email != '')
                    $this->plNotification->handle(['email' => $data['user']->email]);

                $data['response']['message'] = $message;
                $data['response']['data'] = array(
                    'confirm' => false,
                    'error_code' => 5,
                    'message' => $message,
                );
            }
        }

        return $data;
	}
}