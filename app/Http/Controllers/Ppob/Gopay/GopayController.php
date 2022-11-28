<?php

namespace App\Http\Controllers\Ppob\Gopay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Ppob\Gopay\GopayRepository;

class GopayController extends Controller
{
    protected $amount = [20000, 25000, 50000, 100000, 150000, 200000, 250000]; //jumlah yag udah di teteapkan

    public function Topup(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'customer_phone' => 'required',
            'amount' => 'required',
            'service_id' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        if (!in_array($request->amount, $this->amount))
            return response()->json(['success' => false, 'response_code' => 404, 'message' => 'Jumlah nominal tidak diizinkan'], 404);

        $data = (new GopayRepository)->topUp($request, $user_id);

        return $data;
    }
}
