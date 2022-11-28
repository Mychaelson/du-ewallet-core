<?php

namespace App\Http\Controllers\Ppob\Pdam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Pdam\PdamRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class PdamController extends Controller
{
    protected $pdam;

    public function __construct(PdamRepository $pdam)
    {
        $this->pdam = $pdam;
    }

    public function inquiry(Request $request)
    {
        /* $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'customer_phone' => 'required',
            'customer_id' => 'required',
            // 'reff_number' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->pdam->inquiry($request, $user_id); */

        // $url = ENV('RAJABILLER_URL');
        // $param = array(
        //     'method' => 'rajabiller.inq',
        //     'uid' => ENV('RAJABILLER_UID'),
        //     'pin' => ENV('RAJABILLER_PIN'),            
        //     'idpel1' => $request->member_id,
        //     "idpel2" => "",
        //     "idpel3" => "",
        //     'kode_produk' => $request->product_code,
        //     'ref1' => $request->reff_number,
        // );

        // $header = array(
        //     'Content-Type: application/json'
        //     );
    
        // $response = Http::withHeaders($header)->post($url, $param);
        // return $response;

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

        $order_info = [
            'periode' => $request->month,
            'pdam_number' => $request->customer_id,
            'reff_no' => GenerateOrderId('PDAM'),
            'user_id' => $user->id,
            'product_code' => $request->product_code,
            'phone' => $request->customer_phone
        ];

        $inquiry = $this->pdam->addOrder($order_info);

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
