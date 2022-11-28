<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Macros\Accounts\UserInformationMacro;
use App\Macros\Accounts\ValidatePasswordMacro;
use App\Notifications\Accounts\CloseAccountNotification;
use App\Repositories\Accounts\CloseAccountRepository;
use App\Repositories\Accounts\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CloseAccountController extends Controller
{
    public function __construct(
        private ValidatePasswordMacro $vpMacro,
        private UserInformationMacro $uiMacro,
        private CloseAccountRepository $caRepository,
        private UsersRepository $usersRepository,
        private CloseAccountNotification $caNotification
    ) {
    }

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        $data['user'] = auth('api')->user();
        $data['ip'] = $request->ip();
        $location = ip2location($data['ip']);
        $data['location'] = $location->fullAddress();

        //validate fields
        $data = $this->validateFields($data);
        if (! $data['response']['success']) {
            return response($data['response'])->header('Content-Type', 'application/json');
        }

        //execute
        $data = $this->$action($data);

        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function reasons($data)
    {
        $locale = auth()->user()->locale ?? 'en';
        $data['response']['data'] = [
            'reasons' => config('close-account.reasons.'.$locale),
            'emoticons' => config('close-account.reasons.emoticon.'.$locale),
        ];

        return $data;
    }

    private function appeal($data)
    {
        //should validate the password first
        $data = $this->vpMacro->handle($data);
        if (! $data['response']['success']) {
            return $data;
        }

        $user = $data['user'];
        $content = $data['request']['content'];

        //should update/insert to close account table
        $closeAccount = $this->caRepository->getCloseAccountByUserId($user->id);
        if (is_null($closeAccount)) {
            //create new
            $newRow = [
                'user_id' => $user->id,
                'status' => 1,
                'emoticon' => $content['emoticon'],
                'content' => $content['reason'],
            ];
            $closeAccount = $this->caRepository->create($newRow);
        } else {
            $update = [
                'status' => 1,
                'emoticon' => $content['emoticon'],
                'content' => $content['reason'],
            ];
            $this->caRepository->update($user->id, $update);
        }

        //Should get user information and return response
        $userInformation = $this->uiMacro->handle($data);
        $data['response']['message'] = trans('messages.close-account-submitted', ['username' => $user->name ?? $user->username]);
        $data['response']['data'] = [
            'id' => $closeAccount->id,
            'user_id' => $user->id,
            'user' => $userInformation['response']['data'],
            'status' => $user->status,
            'emoticon' => $content['emoticon'],
            'content' => $content['reason'],
            'meta' => $closeAccount->meta,
            'reason' => $content['reason'],
            'approval_by' => $closeAccount->approval_by,
            'approved_at' => $closeAccount->approved_at,
            'updated_at' => $closeAccount->created_at,
            'created_at' => $closeAccount->updated_at,
        ];

        //should generate otp
        generate_otp($user->username, 'close-account');

        return $data;
    }

    private function otp($data)
    {
        generate_otp($data['user']->username, 'close-account');

        $data['response']['message'] = trans('messages.sms-sent', ['phone' => $data['user']->username]);

        return $data;
    }

    private function meta($data)
    {
        //should get close account
        $user = $data['user'];
        $closeAccount = $data['closeAccount'];

        //update close account
        $update = [
            'status' => 2,
            'meta' => json_encode($data['request']['content']),
        ];
        $this->caRepository->update($user->id, $update);

        //Should get user information and return response
        $userInformation = $this->uiMacro->handle($data);
        $data['response']['message'] = trans('messages.close-account-meta-submitted', ['username' => $user->name ?? $user->username]);
        $data['response']['data'] = [
            'id' => $closeAccount->id,
            'user_id' => $user->id,
            'user' => $userInformation['response']['data'],
            'status' => $user->status,
            'emoticon' => $closeAccount->emoticon,
            'content' => $closeAccount->content,
            'meta' => json_encode($data['request']['content']),
            'reason' => $closeAccount->content,
            'approval_by' => $closeAccount->approval_by,
            'approved_at' => $closeAccount->approved_at,
            'updated_at' => $closeAccount->created_at,
            'created_at' => $closeAccount->updated_at,
        ];

        return $data;
    }

    private function close($data)
    {
        //should validate otp
        $otp = validate_otp($data['user']->username, 'close-account', $data['request']['content']['otp']);
        if (is_null($otp)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.otp-invalid');

            return $data;
        }
        if (time() > strtotime($otp->expires_at)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.otp-expired');

            return $data;
        }

        //should get close account
        $user = $data['user'];
        $closeAccount = $data['closeAccount'];
        if ($closeAccount->status != 5) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('close-account-not-completed', ['username' => $user->name ?? $user->username]);

            return $data;
        }

        //update close account status
        $this->caRepository->update($user->id, ['status' => 6]);

        //update user status -> 3
        $this->usersRepository->update(['status' => 3], $user->id);

        //send notification
        if (! is_null($user->email) && $user->email != '') {
            $this->caNotification->handle(['email' => $user->email]);
        }

        //Should get user information and return response
        $userInformation = $this->uiMacro->handle($data, true);
        $data['response']['message'] = trans('messages.account-closed', ['username' => $user->name ?? $user->username]);
        $data['response']['data'] = [
            'id' => $closeAccount->id,
            'user_id' => $user->id,
            'user' => $userInformation['response']['data'],
            'status' => $user->status,
            'emoticon' => $closeAccount->emoticon,
            'content' => $closeAccount->content,
            'meta' => json_encode($data['request']['content']),
            'reason' => $closeAccount->content,
            'approval_by' => $closeAccount->approval_by,
            'approved_at' => $closeAccount->approved_at,
            'updated_at' => $closeAccount->created_at,
            'created_at' => $closeAccount->updated_at,
        ];

        return $data;
    }

    private function validateFields($data)
    {
        $content = $data['request']['content'];
        $skipValidation = ['reasons', 'otp'];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } elseif ($data['action'] == 'appeal') {
            $rules = [
                'password' => 'required',
                'emoticon' => 'required',
                'reason' => 'required',
            ];
        } elseif ($data['action'] == 'meta') {
            $rules = [
                'method' => 'required',
                'data.bank_account' => 'required|numeric',
                'data.bank_account_name' => 'required',
                'data.bank_name' => 'required',
                'data.amount' => 'required|numeric',
            ];
        } elseif ($data['action'] == 'close') {
            $rules = [
                'otp' => 'required|numeric',
            ];
        }

        $validator = Validator::make($content, $rules);
        if ($validator->fails()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            foreach ($validator->errors()->messages() as $field => $value) {
                foreach ($value as $key => $message) {
                    $data['response']['message'] .= "$message ";
                }
            }
        }

        if ($data['action'] == 'meta' || $data['action'] == 'close') {
            $data['closeAccount'] = $this->caRepository->getCloseAccountByUserId($data['user']->id);
            if (is_null($data['closeAccount'])) {
                $data['response']['success'] = false;
                $data['response']['response_code'] = 422;
                $data['response']['message'] = trans('close-account-not-submitted', ['username' => $data['user']->name ?? $data['user']->username]);

                return $data;
            }
        }

        return $data;
    }
}
