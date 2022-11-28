<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletOtpController extends Controller
{
    public function __invoke(Request $request)
    {
        //init data
        $data = init_transaction_data($request);
        $data['ip'] = $request->ip();
        $data['device'] = $request->header('X-Device-Name');
        $data['device_id'] = $request->header('X-Device-ID');

        $location = ip2location($data['ip']);
        $data['location'] = $location->fullAddress();

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = $validator->errors()->first();

            return $data;
        }

        $username = auth()->user()->username;
        generate_otp($username, $request->type);

        $data['response']['message'] = trans('messages.sms-sent', ['phone' => $username]);

        return response()->json($data['response'], $data['response']['response_code']);
    }
}
