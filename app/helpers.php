<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('init_transaction_data')) {
    function init_transaction_data($request, $action = null)
    {
        $data['action'] = $action;
        $data['request']['requestTime'] = date('Y-m-d H:i:s');
        $data['request']['content'] = array_filter($request->all(), function ($value) {
            return $value !== null && $value !== false && $value !== '';
        });
        $data['response']['success'] = true;
        $data['response']['response_code'] = 200;
        $data['response']['message'] = '';
        $data['response']['data'] = [];

        return $data;
    }
}

if (! function_exists('ip2location')) {
    function ip2location($ip)
    {
        $location = (new App\Helpers\Location)->fromIp($ip);

        return $location;
    }
}

if (! function_exists('str_random')) {
    function str_random(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; $i++) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }

        return implode('', $pieces);
    }
}

if (! function_exists('generate_confirm_code')) {
    function generate_confirm_code($userId)
    {
        $hashids = new \Hashids\Hashids(config('app.key'), 10);
        $hash = $hashids->encode($userId);

        return $hash;
    }
}

if (! function_exists('generate_username')) {
    function generate_username($phone, $phoneCode = 62)
    {
        $username = preg_replace('/[^0-9]+/', '', $phone);
        $firstTwo = substr($username, 0, 2);
        if ($firstTwo == '62' || $firstTwo == '08') {
            $username = $firstTwo == '62' ? substr($username, 2) : substr($username, 1);
        }

        $username = $phoneCode.$username;

        return $username;
    }
}

if (! function_exists('generate_otp')) {
    function generate_otp($username, $action, $channel = 'sms')
    {
        $otpRepository = new \App\Repositories\Accounts\OTPRepository(new \App\Models\Accounts\OneTimePasswords());
        $otp = $otpRepository->getOtpByUsername($username);

        if (! is_null($otp)) {
            if (time() > strtotime($otp->expires_at)) {
                //delete and generate new
                $otpRepository->delete($otp->id);
                $otp = $otpRepository->generate($username, $action);
            } else {
                //reuse
                $otpRepository->increment($otp->id);
            }
        } else {
            //create new
            $otp = $otpRepository->generate($username, $action);
        }

        //dispatch notification
        if ($channel == 'sms') {
            $otp->notify(new \App\Notifications\Accounts\OTPNotificationSNS($otp->toArray()));
        } elseif ($channel == 'email') {
            $data['otp'] = $otp;
            $data['email'] = $username;
            /*$mailer = new \App\Notifications\Accounts\ValidateEmailNotification(new \App\Repositories\Services\Mail\EmailPortal\EmailPortalApi());
            $mailer->handle($data);*/
        }

        return $otp;
    }
}

if (! function_exists('validate_otp')) {
    function validate_otp($username, $action, $token)
    {
        $otpRepository = new \App\Repositories\Accounts\OTPRepository(new \App\Models\Accounts\OneTimePasswords());
        $otp = $otpRepository->validate($username, $action, $token);
        if (! is_null($otp)) {
            $otpRepository->delete($otp->id);
        }

        return $otp;
    }
}

if (! function_exists('valid_phone')) {
    function valid_phone(string $phone)
    {
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace('.', '', $phone);
        $phone = str_replace('+', '', $phone);

        // cek apakah no hp mengandung karakter + dan 0-9
        if (! preg_match('/[^+0-9]/', trim($phone))) {
            if (substr(trim($phone), 0, 2) === '62') {
                $phone = '0'.substr(trim($phone), 2);
            } else {
                $phone = trim($phone);
            }
        }

        return (string) $phone;
    }
}

if (! function_exists('GenerateOrderId')) {
    function GenerateOrderId($prefix, $traxid = false)
    {
        $date = date('Ymd');
        if (! $traxid) {
            $random = str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        } else {
            $random = str_pad($traxid, 8, '0', STR_PAD_LEFT);
        }

        return "{$prefix}-{$date}-{$random}";
    }
}

if (! function_exists('cleanphone')) {
    function cleanphone(string $phone)
    {
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace('.', '', $phone);
        $phone = str_replace('+', '', $phone);
        $phone = str_replace('-', '', $phone);

        return (string) $phone;
    }
}

if (! function_exists('getSettings')) {
    function getSettings(string $key, $currency = 'IDR')
    {
        return Cache::rememberForever('settings-'.$currency, function () use ($currency, $key) {
            return App\Models\Ppob\Setting::where('currency', $currency)->where('key', $key)->first();
        });
        // return Setting::all();
    }
}

if (! function_exists('wording_ribu')) {
    function wording_ribu($value)
    {
        $val = intval($value);
        if ($val >= 1000 && $val < 1000000) {
            return $val = ($val / 1000).' Ribu';
        } elseif ($val >= 1000000) {
            return $val = ($val / 1000000).' Juta';
        } else {
            return $val;
        }
    }
}
