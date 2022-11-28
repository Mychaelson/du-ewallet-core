<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Macros\Accounts\RegisterVirtualAccountsMacro;
use App\Macros\Accounts\ValidatePasswordMacro;
use App\Repositories\Accounts\PasswordChangeHistoriesRepository;
use App\Repositories\Accounts\PasswordThrottlesRepository;
use App\Repositories\Accounts\ProfileProgressRepository;
use App\Repositories\Accounts\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function __construct(
        private UsersRepository $usersRepository,
        private ProfileProgressRepository $profileProgressRepository,
        private ValidatePasswordMacro $vpMacro,
        private RegisterVirtualAccountsMacro $rvMacro,
        private PasswordChangeHistoriesRepository $pchRepository,
        private PasswordThrottlesRepository $ptRepository
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

    private function confirm($data)
    {
        //should execute validate password macro here
        $data = $this->vpMacro->handle($data);

        return $data;
    }

    private function otp($data)
    {
        generate_otp($data['user']->username, 'password');

        $data['response']['message'] = trans('messages.sms-sent', ['phone' => $data['user']->username]);

        return $data;
    }

    private function update($data)
    {
        $validateOldPass = $this->usersRepository->validatePasswordHash($data['request']['content']['old_password'], $data['user']->password);
        if (! $validateOldPass) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.old-password-invalid');

            return $data;
        }

        $this->usersRepository->updatePassword($data['request']['content']['new_password'], $data['user']->id);
        $data['response']['message'] = trans('messages.password-updated');

        $this->recordPasswordChange($data['user'], 'update');

        return $data;
    }

    private function reset($data)
    {
        //validate otp
        $content = $data['request']['content'];
        $token = $content['otp'];
        $otp = validate_otp($data['user']->username, 'password', $token);
        if (is_null($otp)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.otp-invalid');

            return response($data['response'])->header('Content-Type', 'application/json');
        }

        if (time() > strtotime($otp->expires_at)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.otp-expired');

            return response($data['response'])->header('Content-Type', 'application/json');
        }

        //validate confirm code
        $confirmCode = generate_confirm_code($data['user']->id);
        $validateConfirmCode = $content['confirm_code'] == $confirmCode;
        if (! $validateConfirmCode) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 401;
            $data['response']['message'] = trans('messages.confirm-code-invalid');

            return $data;
        }

        $this->usersRepository->updatePassword($content['password'], $data['user']->id);
        $data['response']['message'] = trans('messages.password-updated');
        $this->recordPasswordChange($data['user'], 'reset');

        return $data;
    }

    private function register($data)
    {
        //update user data
        $updates = [
            'password' => \Hash::make($data['request']['content']['password']),
            'date_activated' => date('Y-m-d H:i:s'),
            'status' => 1,
            'is_active_password' => 1,
        ];
        $this->usersRepository->update($updates, $data['user']->id);

        //register virtual accounts
        $this->rvMacro->handle($data);

        //update profile progress
        $ppUpdates = [
            'contact' => 1, 'profile' => 1, 'basic' => 1, 'address' => 1,
        ];
        $this->profileProgressRepository->update($ppUpdates, $data['user']->id);

        $data['response']['message'] = trans('messages.password-registered');

        return $data;
    }

    private function validateFields($data)
    {
        $content = $data['request']['content'];
        $skipValidation = ['otp'];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } elseif ($data['action'] == 'update') {
            $rules = [
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ];
        } elseif ($data['action'] == 'reset') {
            $rules = [
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                'confirm_code' => 'required',
                'otp' => 'required|numeric',
            ];
        } elseif ($data['action'] == 'confirm') {
            $rules = [
                'password' => 'required',
            ];
        } elseif ($data['action'] == 'register') {
            $rules = [
                'password' => 'required',
                'confirm_password' => 'required|same:password',
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

            return $data;
        }

        return $data;
    }

    public function getLastPasswordChange(Request $request)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $lastChange = $this->pchRepository->getlast($user->id);

        $response['response']['data'] = [ 'last_updated' => $lastChange ];
        $response['response']['message'] = trans('messages.last-pin-change-found', ['date' => $lastChange]);

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    private function recordPasswordChange($user, $type = 'update')
    {
        $userToken = $this->usersRepository->getAccessTokens($user);

        $userDevice = $userToken->only(
            'user_id',
            'ip',
            'location',
            'id',
            'created_at',
            'updated_at'
        );

        $userDevice['oauth_access_tokens_id'] = $userDevice['id'];
        unset($userDevice['id']);
        $userDevice['created_at'] = date('c');
        $userDevice['updated_at'] = date('c');
        $userDevice['type'] = $type;

        $this->pchRepository->record($userDevice);
    }

    public function pinBlockStatus(Request $request)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $userPinBlockStatus = $this->ptRepository->getThrottleByUserId($user->id);

        if (! $userPinBlockStatus) {
            $response['response']['message'] = trans('messages.pin-unlocked');
        } else {
            if (! $userPinBlockStatus->lock) {
                $response['response']['message'] = trans('messages.pin-unlocked');
            } else {
                $response['response']['message'] = trans('messages.pin-locked');
            }
        }

        $response['response']['data'] = $userPinBlockStatus;

        return Response($response['response'])->header('Content-Type', 'application/json');
    }
}
