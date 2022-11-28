<?php

namespace App\Http\Controllers\Ppob\Pln;

use App\Http\Controllers\Controller;
use App\Repositories\Ppob\Base\PpobRepository;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Pln\PlnRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class PlnController extends Controller
{
    protected $pln;
    protected $ppob;

    public function __construct(PlnRepository $pln, PpobRepository $ppob)
    {
        $this->pln = $pln;
        $this->ppob = $ppob;
    }

    public function inquiry(Request $request)
    {
       /*  $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'customer_phone' => 'required',
            'customer_id' => 'required',
            'service_id' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->pln->inquiry($request, $user_id);

        return $data; */
        // $url = ENV('RAJABILLER_URL');
        // $param = array(
        //     'method' => 'rajabiller.beli',
        //     'uid' => ENV('RAJABILLER_UID'),
        //     'pin' => ENV('RAJABILLER_PIN'),
        //     'kode_produk' => 'PLNPRA',
        //     'idpel' => $request->pln_number,
        //     'nominal' => $request->nominal,
        //     'ref1' => $request->reff_number,
        // );

        // $header = array(
        //     'Content-Type: application/json'
        //     );
    
        // $response = Http::withHeaders($header)->post($url, $param);
        // return $response;


        $response = init_transaction_data($request);
        $user = $request->user();

        $rule = [
            'product_code' => 'required',
            'customer_id' => 'required',
            'total' => 'required',
            'customer_phone' => 'required',
        ];

        $ruleMessage = [
            'product_code.required' => trans('messages.field-required', ['field' => 'product_code']),
            'customer_id.required' => trans('messages.field-required', ['field' => 'customer_id']),
            'total.required' => trans('messages.field-required', ['field' => 'total']),
            'customer_phone.required' => trans('messages.field-required', ['field' => 'customer_phone']),
        ];

        $validator = Validator::make($request->all(), $rule, $ruleMessage);

        if ($validator->fails()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $validator->messages()->first();

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $order_info = [
            'user_id' => $user->id,
            'reff_no' => GenerateOrderId('PLN'),
            'nominal' => $request->total,
            'pln_number' => $request->customer_id,
            'product_code' => $request->product_code,
            'phone' => $request->customer_phone
        ];

        $inquiry = $this->pln->addOrder($order_info);

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

    public function token (Request $request, $billId)
    {
        $response = init_transaction_data($request);

        $bill = $this->ppob->getTransactionByInvoiceNo($billId);
        $part_inv = explode('-', $billId);

        if (!isset($bill) || $bill->status != 3 || $part_inv[0] != 'PLN') {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = trans('messages.transaction-not-found');

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $reqPayment = json_decode($bill->req_payment, true);
        $resPayment = json_decode($bill->res_payment, true);

        $repo = App::make($reqPayment['service']);
        $formatRes = $repo->formatPaymentToken($resPayment);


        $response['response']['message'] = 'PLN token found';
        $response['response']['data'] = $formatRes;
        return Response($response['response'])->header('Content-Type', 'application/json');
    }
}
