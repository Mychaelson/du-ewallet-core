<?php

namespace App\Repositories\Ppob\Pln;

use App\Models\Ppob\DigitalProducts;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Models\Ppob\DigitalTransactions;
use App\Repositories\Payment\BillRepository;
use App\Repositories\Ppob\Base\PpobRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PlnRepository
{
    private $ppobRepository, $billRepository;

    public function __construct(
        PpobRepository $ppobRepository,
        BillRepository $billRepository
    )
    {
        $this->ppobRepository = $ppobRepository;
        $this->billRepository = $billRepository;
    }


    public function inquiry($request,$user_id)
    {
        $product = DigitalProducts::where('code', $request->code)->first();
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

            
        $service = $product->service()->where('service_id',$request->service_id)->active()->first();
        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
            
        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
        


        $order_id = GenerateOrderId('TR');
        $phone = cleanphone($request->customer_phone);
        $params = [
            'customer_phone' => $phone,
            'customer_id' => $request->customer_id,
            'code' => $service->code,
            'amount' => (int) $request->input('amount', 0),
            'reff_number' => $order_id,
            'action' => 'pln'
        ];
        $nominal = ($request->has('amount') && $request->amount > 0)? wording_ribu($request->amount): '';
        
        // call vendor/provider service
        $res = (new $service->switcher->service)->setParams($params)->inquiry();

        $operation_cost = (int) getSettings('operation_cost')->first()->value;
        $price = $product->price;
        $admin_fee = $product->admin_fee;
        $base_price = (($price + $admin_fee) + (int) $operation_cost);
        
        if($res['status'] != 'failed') {

            // $base_price = ($res['data']['amount'] + $service->admin_fee + (int) $operation_cost);
        
            $param = [
                'order_id' => $order_id,
                'phone' => $phone,
                'currency' => $product->currency,
                'user_id' => $user_id,
                'price' => $price,
                'admin_fee' => $admin_fee,
                'total' => ($price) + ($admin_fee),
                'base_price' => $base_price,
                'customer_id' => $request->customer_id,
                'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'product_snap' => array_merge($product->toArray(), ['description' => $product->description . ' ' .$nominal]),
                'category' => 'pln',
                'request_data' => $params,
                'inquiry_data' => $res['inquiry_data'] ?? [],
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'type' => 1,
                'meta' => [
                    'inquiry' => 'App\Repositories\Ppob\Pln\PlnRepository',
                    // 'payment' => url('/api/pln/payment')
                ],
                'payment_information' => []
            ];
            // insert to DB
            $transaction = DigitalTransactions::create($param);
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'data' => new ResultResource($transaction),
                'meta' => [
                    'execution_time' => 0
                ]
            ], 200);
        }

        $message = isset($res['data']['note'])? $res['data']['note']: trans('error.error_transaction');
        return response()->json([
            'success' => false,
            'response_code' => 422,
            'message' => $message,
            'meta' => [
                'execution_time' => 0
            ]
        ], 422);
    }

    public function addOrder($data)
    {
        $user_id = $data['user_id'];
        $invoice_no = $data['reff_no'];
        $productInfo = $this->ppobRepository->findProductByProductCode($data['product_code']);

        if (!isset($productInfo)) {
            return [
                'status' => false,
                'message' => trans('messages.product-not-found', ['code' => $data['product_code']])
            ];
        }

        if (!$productInfo->status) {
            return [
                'status' => false,
                'message' => trans('error.service_not_active')
            ];
        }

        $inquiryInfo = [
            'pln_number' => $data['pln_number'],
            'nominal' => (double) $data['nominal'],
            'reff_number' => $data['reff_no'],
            'product_code' => $productInfo->service_product_code,
            'service' => $productInfo->service_path,
            'phone' => $data['phone']
        ];

        DB::beginTransaction();
        try {
            // $inquiry = (new $productInfo->service_path)->setParamsPln($inquiryInfo);
            // $inquiry = json_decode($inquiry, true);

            // if (!isset($inquiry)) {
            //     return [
            //         'status' => false,
            //         'message' => trans('error.inquiry_failed')
            //     ];
            // }

            // if ($inquiry['error']) {
            //     return [
            //         'status' => false,
            //         'message' => $inquiry['error']
            //     ];
            // }

            $transactionInfo = [
                'user_id' => $user_id, 
                'product_code' => $productInfo->code, 
                'label_id' => 2, 
                'invoice_no' => $invoice_no, 
                'product_type' => $productInfo->product_type,  
                'price_sell' => $productInfo->price_sell,
                'admin_fee' => $productInfo->admin_fee, 
                'discount' => $productInfo->discount,
                'status' => 2,
                'service_id' => $productInfo->service_id,
                'req_inquiry' => json_encode($inquiryInfo),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $transactionInfo['total'] = $transactionInfo['price_sell'] + $transactionInfo['admin_fee'] - $transactionInfo['discount'];

            $transaction = $this->ppobRepository->insertTransaction($transactionInfo);
            $transactionData = $this->ppobRepository->getTransactionById($transaction, 'plnToken');

            $createBill = $this->billRepository->createTransactionBill($invoice_no, 'App\Repositories\Ppob\Pln\PlnRepository');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        // if ($inquiry['STATUS'] != '00') {
        //     $status = [
        //         'status' => 1
        //     ];
        //     $this->ppobRepository->updateTransactionById($transaction, $status);
        //     return [
        //         'status' => false,
        //         'message' => $inquiry['KET']
        //     ];
        // }
        return [
            'status' => true,
            'message' => trans('messages.inquiry-success', ['product' => 'PLN Token']),
            'data' => $transactionData
        ];
    }

    public function payment ($invoice_no)
    {
        $transaction = $this->ppobRepository->getTransactionByInvoiceNo($invoice_no);

        if (!isset($transaction)) {
            return [
                'status' => false,
                "message" => trans('error.data_not_found')
            ];
        }

        $inquiryInfo = json_decode($transaction->req_inquiry, true);

        $repo = App::make($inquiryInfo['service']);
        $inquiry = $repo->setParamsPln($inquiryInfo);

        $updateTableTransactionInfo = [
            'req_payment' => $inquiryInfo,
            'res_payment' => $inquiry['data']
        ];

        if (!$inquiry['status']) {
            $status = [
                'status' => 1
            ];
            $this->ppobRepository->updateTransactionById($transaction->id, $status);
            $this->ppobRepository->updateTransactionByInvoice($invoice_no, $updateTableTransactionInfo);
            return [
                'status' => false,
                'message' => $inquiry['message']
            ];
        }

        $inqueryAdditionalInfo = $repo->setTransactionInquiry($inquiry['data'], $transaction->total);

        $updateTableTransactionInfo = $updateTableTransactionInfo + $inqueryAdditionalInfo;

        $update = $this->ppobRepository->updateTransactionByInvoice($invoice_no, $updateTableTransactionInfo);

        return [
            'status' => true,
            'message' => 'ok',
            'data' => $inquiry['data']
        ];
    }
}
