<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\PasswordThrottlesRepository;
use App\Repositories\Accounts\PasswordWrongHistoriesRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Wallet\WalletLimitRepository;
use App\Repositories\Wallet\WalletsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(
        private UsersRepository $usersRepository,
        private PasswordThrottlesRepository $ptRepository,
        private PasswordWrongHistoriesRepository $pwhRepository,
        private WalletsRepository $walletsRepository,
        private WalletLimitRepository $walletLimitsRepository
    ) {}

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        $data['ip'] = $request->ip();
        $data['device'] = $request->header('X-Device-Name');
        $data['device_id'] = $request->header('X-Device-ID');
        $location = ip2location($data['ip']);
        $data['location'] = $location->fullAddress();

        //validate fields
        $data = $this->validateFields($data);
        if (! $data['response']['success']) {
            return response($data['response'])->header('Content-Type', 'application/json');
        }

        if (isset($data['username'])) {
            //validate username
            $data['user'] = $this->usersRepository->getUserByUsername($data['username']);
            $data['otpAction'] = is_null($data['user']) ? 'register' : 'signin';

            if ($action == 'register' || $action == 'signin') {
                //validate otp
                $token = $data['request']['content']['otp'];
                $otp = validate_otp($data['username'], $data['otpAction'], $token);
                if (is_null($otp)) {
                    $data['response']['success'] = false;
                    $data['response']['response_code'] = 422;
                    $data['response']['message'] = trans('messages.otp-invalid');

                    return response($data['response'])->header('Content-Type', 'application/json');
                }

                if ($otp->isExpired()) {
                    $data['response']['success'] = false;
                    $data['response']['response_code'] = 422;
                    $data['response']['message'] = trans('messages.otp-expired');

                    return response($data['response'])->header('Content-Type', 'application/json');
                }
            }
        }

        //execute
        $data = $this->$action($data);

        return response()->json($data['response']);
    }

    private function otp($data)
    {
        $username = $data['username'];
        generate_otp($username, $data['otpAction']);

        

        $data['response']['message'] = trans('messages.sms-sent', ['phone' => $username]);
        $data['response']['data']['phone'] = $data['request']['content']['phone'];
        $data['response']['data']['phonecode'] = $data['request']['content']['phonecode'];
        $data['response']['data']['phone_user'] = $username;
        $data['response']['data']['is_active'] = $data['otpAction'] == 'signin';

        return $data;
    }

    private function register($data)
    {
        $content = $data['request']['content'];

        //should check if username has been registered or not
        $user = $data['user'];
        if (! is_null($user)) {
            return $this->signin($data);
        }

        //should create
        $user = [
            'username' => $data['username'],
            'phone' => $content['phone'],
            'phonecode' => $content['phonecode'],
        ];
        DB::beginTransaction();

        try {
            $data['user'] = $this->usersRepository->create($user);

            // should create wallet
            $data['wallet'] = $this->walletsRepository->createWallet($data['user']->id);

            // should create wallet limit
            $walletLimitData = [
                'wallet' => $data['wallet']->id,
                'withdraw_daily' => 2000000,
                'transfer_daily' => 2000000,
                'payment_daily' => 2000000,
                'topup_daily' => 2000000,
                'switching_max' => 2000000,
                'max_balance' => 2000000,
                'transaction_monthly' => 10000000,
                'free_withdraw' => 3,
                'max_group_transfer' => 3,
                'max_group_withdraw' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $data['wallet_limit'] = $this->walletLimitsRepository->addWalletLimit($walletLimitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //return $this->errorResponse($e->getMessage(), $e->getCode());
            return false;
        }

        //should sign in
        return $this->signin($data);
    }

    private function signin($data)
    {
        //should get the user first
        $user = $data['user'];
        if (is_null($user)) {
            return $this->register($data);
        }

        //should check user status
        if ($user->status != 1) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 401;

            if ($user->status == 2) {
                $throttle = $this->ptRepository->getThrottleByUserId($user->id);
                $penaltyDuration = round((strtotime($throttle->expires_on) - time()) / 60);
                $data['response']['message'] = trans('messages.pin-confirmation-penalty', ['penalty' => $penaltyDuration]);
            } elseif ($user->status == 3) {
                $data['response']['message'] = trans('messages.account-closed', ['username' => $user->username]);
            }

            return $data;
        }

        //should get available tokens
        $activeToken = 0;
        $availableTokens = $this->usersRepository->getAccessTokens($user, 'all');
        foreach ($availableTokens as $key => $availableToken) {
            //should recycle token 
            if ($availableToken->device_id == $data['device_id'] && !$availableToken->revoked) 
                $availableToken->revoke();

            if (!$availableToken->revoked)
                $activeToken++;
        }

        if ($activeToken > 5 && $user->watch_status != 1) {
            $this->usersRepository->update(['watch_status' => 1], $user->id);
            $this->pwhRepository->create([
                'user_id' => $user->id,
                'ip' => $data['ip'],
                'location' => $data['location'],
                'device' => $data['device'],
                'device_id' => $data['device_id'],
                'message' => trans('messages.watch-status-updated'),
            ]);
        }

        $token = $this->usersRepository->createToken($data);

        //generate response
        $data['message'] = trans('messages.login-success');
        $data['response']['data']['token_type'] = 'Bearer';
        $data['response']['data']['expires_in'] = Carbon::parse($token->token->expires_at)->subtract(now())->timestamp;
        $data['response']['data']['access_token'] = $token->accessToken;

        return $data;
    }

    private function logout($data)
    {
        $user = auth('api')->user();
        $data['message'] = trans('messages.logout-success');
        if (is_null($user)) {
            return $data;
        }

        //should get current token
        $token = $this->usersRepository->getAccessTokens($user);

        //should revoke token
        $this->usersRepository->revokeTokens($user, $token->id);

        return $data;
    }

    private function strength($data)
    {
        $blacklist = [
            '123456', '234567', '345678', '456789', '567890',
            '098765', '987654', '876543', '765432', '654321',
            '111111', '222222', '333333', '444444', '555555',
            '666666', '777777', '888888', '999999', '000000',
        ];
        if (in_array($data['request']['content']['password'], $blacklist)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 401;
            $data['response']['message'] = trans('validation.pin-combination-insecure');
        }

        return $data;
    }

    private function validateFields($data)
    {
        $skipValidation = ['logout'];
        $ruleMessage = [];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } elseif ($data['action'] == 'otp') {
            $rules = [
                'phonecode' => 'required|numeric',
                'phone' => 'required|numeric',
            ];
            $ruleMessage = [
                'phonecode.required' => trans('messages.field-required', ['field' => 'phonecode']),
                'phone.required' => trans('messages.field-required', ['field' => 'phone']),
                'phonecode.numeric' => trans('messages.field-number', ['field' => 'phonecode']),
                'phone.numeric' => trans('messages.field-number', ['field' => 'phone']),
            ];
        } elseif ($data['action'] == 'register' || $data['action'] == 'signin') {
            $rules = [
                'phonecode' => 'required|numeric',
                'phone' => 'required|numeric',
                'otp' => 'required|numeric|digits:6',
            ];

            $ruleMessage = [
                'phonecode.required' => trans('messages.field-required', ['field' => 'phonecode']),
                'phone.required' => trans('messages.field-required', ['field' => 'phone']),
                'otp.required' => trans('messages.field-required', ['field' => 'otp']),
                'phonecode.numeric' => trans('messages.field-number', ['field' => 'phonecode']),
                'phone.numeric' => trans('messages.field-number', ['field' => 'phone']),
                'otp.numeric' => trans('messages.field-number', ['field' => 'otp']),
                'otp.digits' => trans('messages.field-digit', ['field' => 'otp', 'number'=>'6']),
            ];
        } elseif ($data['action'] == 'strength') {
            $rules = [
                'password' => 'required',
            ];

            $ruleMessage = [
                'password.required' => trans('messages.field-required', ['field' => 'password']),
            ];
        }

        $validator = Validator::make($data['request']['content'], $rules, $ruleMessage);
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

        if (isset($data['request']['content']['phonecode']) && isset($data['request']['content']['phone'])) {
            $data['username'] = generate_username(
                $data['request']['content']['phone'],
                $data['request']['content']['phonecode']
            );
        }

        return $data;
    }
}
