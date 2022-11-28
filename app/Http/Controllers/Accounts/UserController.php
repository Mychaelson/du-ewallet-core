<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Macros\Accounts\RegisterVirtualAccountsMacro;
use App\Macros\Accounts\UserInformationMacro;
use App\Repositories\Accounts\PhoneChangesRepository;
use App\Repositories\Accounts\ProfileProgressRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\RedisRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(
        private RedisRepository $redisRepository,
        private UsersRepository $usersRepository,
        private PhoneChangesRepository $phoneChangesRepository,
        private ProfileProgressRepository $profileProgressRepository,
        private UserInformationMacro $uiMacro,
        private RegisterVirtualAccountsMacro $rvaMacro,
    ) {
    }

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        $data['user'] = auth('api')->user();
        $data['ip'] = $request->ip();

        //validate field
        $data = $this->validateFields($data);
        if (! $data['response']['success']) {
            return response($data['response'])->header('Content-Type', 'application/json');
        }

        //execute
        $data = $this->$action($data);

        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function session($data)
    {
        if (isset($data['request']['content']['type']) && $data['request']['content']['type'] == 'all') {
            $token = $this->usersRepository->getAccessTokens($data['user'], 'all');
        } else {
            $token = $this->usersRepository->getAccessTokens($data['user']);
        }

        $data['response']['data'] = $token->toArray();

        return $data;
    }

    private function revoketokens($data)
    {
        $this->usersRepository->revokeTokens($data['user'], $data['request']['content']['id']);
        $data['response']['message'] = trans('messages.token-revoked');

        return $data;
    }

    private function update($data)
    {
        $content = $data['request']['content'];

        //update data
        $this->usersRepository->update($content, $data['user']->id);

        //refresh data
        $username = $content['username'] ?? $data['user']->username;
        $data['user'] = $this->usersRepository->getUserByUsername($username);

        //should trigger email validation if there is email content
        if (isset($content['email'])) {
            generate_otp($content['email'], 'email-verification', 'email');
            $data['response']['message'] = trans('messages.verification-mail-sent', ['mail' => $content['email']]);

            return $data;
        }

        //should update user progress
        if (isset($content['place_of_birth']) && isset($content['date_of_birth'])) {
            $this->profileProgressRepository->update(['profile' => 1], $data['user']->id);
        }

        $data = $this->uiMacro->handle($data, true);
        $data['response']['message'] = trans('messages.user-updated');

        return $data;
    }

    private function validatenickname($data)
    {
        $value = $data['request']['content']['nickname'];
        $validateNickname = $this->usersRepository->getUserByField('nickname', $value);
        
        if (! is_null($validateNickname)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.duplicate-nickname', ['nickname' => $value]);
        }

        return $data;
    }

    private function search($data)
    {
        $content = $data['request']['content'];
        $users = $this->usersRepository->search($content['username']);
        $data['response']['data'] = $users->toArray();

        return $data;
    }

    private function changephonenumber($data)
    {
        $content = $data['request']['content'];
        $user = $data['user'];

        //should check password hash
        $validatePassword = $this->usersRepository->validatePasswordHash($content['password'], $user->password);
        if (! $validatePassword) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 401;
            $data['response']['message'] = trans('messages.password-invalid');

            return $data;
        }

        //should generate username and validate
        $username = generate_username($content['phone'], $content['phonecode']);
        $validateUsername = $this->usersRepository->getUserByField('username', $username);
        if (! is_null($validateUsername)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.duplicate-phone-number', ['username' => $username]);

            return $data;
        }

        //should insert into phone change table
        $newNumber = [
            'user_id' => $user->id,
            'phone' => $username,
            'phone_code' => $content['phonecode'],
            'progress' => 'otp-sent',
            'ip' => $data['ip'],
        ];
        $this->phoneChangesRepository->create($newNumber);

        //should send otp
        generate_otp($username, 'change-phone-number');

        $data['response']['message'] = trans('messages.sms-sent', ['phone' => $username]);

        return $data;
    }

    private function verifyphonenumber($data)
    {
        $content = $data['request']['content'];
        $user = $data['user'];

        //should get the phone change log
        $phoneChangeLog = $this->phoneChangesRepository->getLatestPhoneChangesByUserId($user->id);
        if (is_null($phoneChangeLog)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.phone-change-invalid');

            return response($data['response'])->header('Content-Type', 'application/json');
        }

        //should validate the otp
        $otp = validate_otp($phoneChangeLog->phone, 'change-phone-number', $content['otp']);
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

        //should update the user data
        $updates = [
            'username' => $phoneChangeLog->phone,
            'phone' => $phoneChangeLog->phone,
            'phone_code' => $phoneChangeLog->phone_code,
        ];
        $this->usersRepository->update($updates, $user->id);
        $data['user']->username = $phoneChangeLog->phone;
        $data['user']->phone = $phoneChangeLog->phone;
        $data['user']->phone_code = $phoneChangeLog->phone_code;

        //should reregister va
        $this->rvaMacro->handle($data);

        //remove information cache
        $this->redisRepository->del('userInformation-'.$data['user']->id);

        //mark phone change process to complete
        $this->phoneChangesRepository->complete($phoneChangeLog->id);

        $data['response']['message'] = trans('messages.phone-change-completed', ['phone' => $phoneChangeLog->phone]);

        return $data;
    }

    private function verifyemail($data)
    {
        $content = $data['request']['content'];
        $user = $data['user'];

        //should validate the otp
        $otp = validate_otp($user->email, 'email-verification', $content['otp']);
        if (is_null($otp)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.otp-invalid');

            //return response($data['response'])->header('Content-Type', 'application/json');
            return $data;
        }
        if (time() > strtotime($otp->expires_at)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = trans('messages.otp-expired');

            //return response($data['response'])->header('Content-Type', 'application/json');
            return $data;
        }

        //should update the user data
        $updates = ['email_verified' => 1];
        $this->usersRepository->update($updates, $user->id);

        //remove information cache
        $this->redisRepository->del('userInformation-'.$data['user']->id);

        $data['response']['message'] = trans('messages.email-verified', ['mail' => $user->email]);

        return $data;
    }

    private function validateFields($data)
    {
        $skipValidation = ['information', 'session'];
        $content = $data['request']['content'];
        $ruleMessage = [];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } elseif ($data['action'] == 'revoketokens') {
            $rules = ['id' => 'required'];
            $ruleMessage = [
                'id.required' => trans('messages.field-required', ['field' => 'id']),
            ];
        } elseif ($data['action'] == 'validatenickname') {
            $rules = ['nickname' => 'required|min:4'];
            $ruleMessage = [
                'nickname.required' => trans('messages.field-required', ['field' => 'nickname']),
                'nickname.digits' => trans('messages.field-digit', ['field' => 'nickname', 'number'=>'4']),
            ];
        } elseif ($data['action'] == 'search') {
            $rules = ['username' => 'required|min:4'];
            $ruleMessage = [
                'username.required' => trans('messages.field-required', ['field' => 'username']),
                'username.digits' => trans('messages.field-digit', ['field' => 'username', 'number'=>'4']),
            ];
        } elseif ($data['action'] == 'changephonenumber') {
            $rules = [
                'phonecode' => 'required|numeric',
                'phone' => 'required|numeric',
                'password' => 'required',
            ];
            $ruleMessage = [
                'phonecode.required' => trans('messages.field-required', ['field' => 'phonecode']),
                'phone.required' => trans('messages.field-required', ['field' => 'phone']),
                'password.required' => trans('messages.field-required', ['field' => 'password']),
                'phonecode.numeric' => trans('messages.field-number', ['field' => 'phonecode']),
                'phone.numeric' => trans('messages.field-number', ['field' => 'phone']),
            ];
        } elseif ($data['action'] == 'verifyphonenumber' || $data['action'] == 'verifyemail') {
            $rules = [
                'otp' => 'required|numeric|digits:6',
            ];
            $ruleMessage = [
                'otp.required' => trans('messages.field-required', ['field' => 'otp']),
                'otp.numeric' => trans('messages.field-number', ['field' => 'otp']),
                'otp.digits' => trans('messages.field-digit', ['field' => 'otp', 'number'=>'6']),
            ];
        } elseif ($data['action'] == 'update') {
            $acceptedField = ['username', 'name', 'nickname', 'email',
                'email_hash', 'phone', 'phone_code', 'avatar', 'place_of_birth',
                'date_of_birth', 'gender', 'blood_type', 'marital_status', 'religion',
                'group_id', 'status', 'watch_status', 'user_type', 'verified',
                'locale', 'timezone', 'location', 'referral_by', 'referral_code',
                'referral_change_count', 'telegram_id', 'whatsapp', 'whatsapp_active', 'gcm_token',
                'device_token', 'onesignal_id', 'remember_token', 'nfc_device', 'nfc_identify',
                'main_device', 'main_device_name', 'date_suspended', 'suspended_reason', ];
            foreach ($content as $key => $value) {
                if (! in_array($key, $acceptedField)) {
                    $data['response']['success'] = false;
                    $data['response']['response_code'] = 422;
                    $data['response']['message'] = trans('messages.invalid-field', ['field' => $key]);

                    return $data;
                }

                $rules[$key] = 'required';
                if ($key == 'email') {
                    $rules[$key] .= '|email';

                    //should be unique
                    $validateEmail = $this->usersRepository->getUserByField($key, $value);
                    if (! is_null($validateEmail)) {
                        $data['response']['success'] = false;
                        $data['response']['response_code'] = 422;
                        $data['response']['message'] = trans('messages.duplicate-email', ['email' => $value]);

                        return $data;
                    }

                    //since email is updated, should set verified to 0
                    $data['request']['content']['email_verified'] = 0;
                }

                if ($key == 'nickname') {
                    $rules[$key] .= '|min:4';
                    //should be unique
                    $validateNickname = $this->usersRepository->getUserByField($key, $value, $ruleMessage);
                    if (! is_null($validateNickname)) {
                        $data['response']['success'] = false;
                        $data['response']['response_code'] = 422;
                        $data['response']['message'] = trans('messages.duplicate-nickname', ['nickname' => $value]);

                        return $data;
                    }
                }

                if ($key == 'gender') {
                    $rules[$key] .= '|numeric';
                }

                if ($key == 'location') {
                    $data['request']['content']['location']['ip'] = $data['ip'];
                    $location = ip2location($data['ip']);
                    $data['request']['content']['location']['location'] = $location->fullAddress();
                    $data['request']['content']['location'] = json_encode($data['request']['content']['location']);
                }
            }
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

    public function readQrCode(Request $request)
    {
        $response = init_transaction_data($request);
        $userId = $request->input('qr_id', false);
        $userId = decode_qr_code($userId);

        $userId = $userId[0] ?? 0;
        $data['user'] = $this->usersRepository->getUserById($userId);

        if (! $data['user']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 404;
            $response['response']['message'] = trans('messages.user-not-found');
        } else {
            $info = $request->has('activity') ? $request->all() : ['activity' => 'UP'];

            $meta = $info;

            $response['response']['data'] = $this->information($data)['response']['data'];
            $response['response']['meta'] = $meta;
            $response['response']['message'] = trans('messages.user-found');
        }

        return response($response['response'])->header('Content-Type', 'application/json');
    }
}
