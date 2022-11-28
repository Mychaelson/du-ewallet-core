<?php

namespace App\Http\Controllers\Ppob\Bpjs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Bpjs\BpjsRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class BpjsController extends Controller
{
    protected $bpjs;

    public function __construct(BpjsRepository $bpjs)
    {
        $this->bpjs = $bpjs;
    }

    public function inquiry(Request $request)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'product_code' => 'required',
            'month' => 'required',
            'customer_id' => 'required',
            'customer_phone' => 'required',
        ]);

        if ($validator->fails()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $validator->messages()->first();

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $product_code = $request->product_code;
        // check product_code valid or not

        $periode = $request->month;
        $bpjs_member_id = $request->customer_id;
        $invoice_no = GenerateOrderId('BPJS');
        
        // dd($user->id, $invoice_no, $period, $bpjs_member_id);

        $order_info = [
            'periode' => $periode,
            'bpjsMemberId' => $bpjs_member_id,
            'reff_no' => $invoice_no,
            'user_id' => $user->id,
            'product_code' => $product_code,
            'phone' => $request->customer_phone
        ];

        $inquiry = $this->bpjs->add_transaction($order_info);

        if (isset($inquiry) && !$inquiry['status']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $inquiry['message'] ?? $inquiry;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = $inquiry['message'];
        $response['response']['data'] = $inquiry['data'];

        return Response($response['response'])->header('Content-Type', 'application/json');
    }
}
