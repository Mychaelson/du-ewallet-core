<?php

namespace App\Repositories\Ppob\Pdam;

use App\Models\Ppob\DigitalCategories;
use App\Models\Ppob\DigitalProducts;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Models\Ppob\DigitalTransactions;
use App\Repositories\Payment\BillRepository;
use App\Repositories\Ppob\Base\PpobRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PdamRepository
{
    private $ppobRepository, $billRepository;

    public function __construct(
        PpobRepository $ppobRepository,
        BillRepository $billRepository
    ){
        $this->ppobRepository = $ppobRepository;
        $this->billRepository = $billRepository;
    }
    
    public function getList($request)
    {
        $category = DigitalCategories::with([
            'products' => function($query) use ($request) {
                // $query->where('currency',$request->input('currency', 'IDR'));
                if ($request->has('q')) {
                    $query->where('name', 'LIKE', '%'. strtoupper($request->q) .'%')->orWhere('provider', 'ILIKE', '%'. $request->q .'%')->active();
                }
            }
        ])->where('slug','pdam')->firstOrFail();

        $products = $category->products;
        $grouped = $products->mapToGroups(function ($item, $key) {
            return [$item['provider'] => [
                'id' => $item['id'],
                'code' => $item['code'],
                'name' => $item['name'],
                'image' => $item['image']
            ]];
        });
        return response()->json([
            'success' => true,
            'response_code' => 200,
            'data' => $grouped
        ], 200);

    }

    // public function inquiry($request,$user_id)
    // {
    //     $product = DigitalProducts::find($request->product_id);
    //     if(! $product)
    //         return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

            
    //     $service = $product->service()->active()->first();
    //     if(! $service)
    //         return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
            
    //     if(!$service->switcher)
    //         return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
        


    //     // $order_id = $request->reff_number;
    //     $order_id = isset($request->reff_number) ? $request->reff_number : GenerateOrderId('TR');
    //     $phone = cleanphone($request->customer_phone);
        
    //     $params = [
    //         'customer_phone' => $phone,
    //         'customer_id' => $request->customer_id,
    //         'code' => $service->code,
    //         'reff_number' => $order_id,
    //         'action' => 'pdam'
    //     ];        
        
    //     // call vendor/provider service
    //     \Log::debug('PDAM-repo :: getRequest '.json_encode($params) );
    //     $res = (new $service->switcher->service)->setParams($params)->inquiry();
    //     \Log::debug('PDAM-repo :: getResponse '.json_encode($res));
        
    //     $operation_cost = (int) getSettings('operation_cost')->first()->value;
    //     $price = $product->price;
    //     $admin_fee = $product->admin_fee;
    //     $base_price = (($price + $admin_fee) + (int) $operation_cost);
        
    //     if($res['status'] != 'failed') {


    //         $param = [
    //             'order_id' => $order_id,
    //             'phone' => $phone,
    //             'currency' => $product->currency,
    //             'user_id' => $user_id,
    //             'price' => $price,
    //             'admin_fee' => $admin_fee,
    //             'total' => $price + ($admin_fee),
    //             'base_price' => $base_price,
    //             'customer_id' => $request->customer_id,
    //             'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
    //             'service' => $service->switcher->service,
    //             'biller_id' => $service->switcher->biller_id,
    //             'code' => $product->code,
    //             'product_snap' => array_merge($product->toArray(), ['description' => $product->description . ' ' .$nominal]),
    //             'category' => 'pdam',
    //             'request_data' => json_encode($params),
    //             'inquiry_data' => json_encode($res['inquiry_data']) ?? "[]",
    //             'result' => json_encode($res['data']),
    //             'response_data' => json_encode($res['response_data']),
    //             'type' => 1,
    //             'meta' => json_encode([
    //                 'inquiry' => 'App\Repositories\Ppob\Pdam\PdamRepository',
    //                 // 'payment' => url('/api/pdam/payment')
    //             ]),
    //             'payment_information' => "[]"
    //         ];
    //         // if(isset($request->reff_number)){
    //         //     $param = [
    //         //         'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
    //         //         'category' => 'pdam',
    //         //         'request_data' => $params,
    //         //         'inquiry_data' => $res['inquiry_data'] ?? [],
    //         //         'result' => $res['data'],
    //         //         'response_data' => $res['response_data'],
    //         //         'type' => 1,//1 inquiry ,0 create-add 
    //         //         'updated_at' => now()
    //         //     ];
    
    //         //     // udpate to DB
    //         //     $ppobid = $this->ppobRepository->updateDigitalTransaksi($order_id,$param);
    //         //     $transaction = $this->ppobRepository->getDetailByOrder_id($order_id);
    //         //     \Log::info('PDAM-repo :: update DT Aman');

    //         // }

    //         $createID = $this->ppobRepository->insertIdDigitalTransaksi($param);
    //         $transaction = $this->ppobRepository->getDetail($createID);
    //         \Log::info('PDAM-repo :: insert DT Aman');
            
    //         // if (isset($request->channel) && $request->channel == 'nusapay')
    //         //     $this->billRepository->createBill($order_id);

    //         return response()->json([
    //             'success' => true,
    //             'response_code' => 200,
    //             'data' => new ResultResource($transaction),
    //             'meta' => [
    //                 'execution_time' => 0
    //             ]
    //         ], 200);
    //     }

    //     $message = isset($res['data']['note'])? $res['data']['note']: trans('error.error_transaction');
    //     return response()->json([
    //         'success' => false,
    //         'response_code' => 422,
    //         'message' => $message,
    //         'meta' => [
    //             'execution_time' => 0
    //         ]
    //     ], 422);
    // }

    public function addOrder($data){
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
            'pdam_number' => $data['pdam_number'],
            'periode' => $data['periode'],
            'reff_number' => $data['reff_no'],
            'product_code' => $productInfo->service_product_code,
            'service' => $productInfo->service_path,
            'provider' => $productInfo->name,
            'phone' => $data['phone']
        ];

        DB::beginTransaction();
        try {
            $inquiry = (new $productInfo->service_path)->setParamsPdam($inquiryInfo);
            $inquiry = json_decode($inquiry, true);

            if (!isset($inquiry)) {
                return [
                    'status' => false,
                    'message' => trans('error.inquiry_failed')
                ];
            }

            if (isset($inquiry['error'])) {
                return [
                    'status' => false,
                    'message' => $inquiry['error']
                ];
            }

            $transactionInfo = [
                'user_id' => $user_id, 
                'product_code' => $productInfo->code , 
                'label_id' => 2, 
                'invoice_no' => $invoice_no, 
                'product_type' => $productInfo->product_type,  
                'admin_fee' => $productInfo->admin_fee, 
                'discount' => $productInfo->discount, 
                'status' => 2,
                'service_id' => $productInfo->service_id,
                'req_inquiry' => json_encode($inquiryInfo),
                'res_inquiry' => json_encode($inquiry),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $additonalTransactionInfo = (new $productInfo->service_path)->setTransactionInquiry($inquiry, 0, true);
            $transactionInfo = $transactionInfo + $additonalTransactionInfo;

            $transactionInfo['total'] = $transactionInfo['price_sell'] + $transactionInfo['admin_fee'] - $transactionInfo['discount'];
            $transactionInfo['profit'] = $transactionInfo['total'] - ($transactionInfo['price_service'] + $transactionInfo['admin_fee_service']);

            $transaction = $this->ppobRepository->insertTransaction($transactionInfo);
            $transactionData = $this->ppobRepository->getTransactionById($transaction, 'pdam');

            $createBill = $this->billRepository->createTransactionBill($invoice_no, 'App\Repositories\Ppob\Pdam\PdamRepository');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        if ($inquiry['STATUS'] != '00') {
            $status = [
                'status' => 1
            ];
            $this->ppobRepository->updateTransactionById($transaction, $status);
            return [
                'status' => false,
                'message' => $inquiry['KET']
            ];
        } else {
            return [
                'status' => true,
                'message' => trans('messages.inquiry-success', ['product' => 'BPJS']),
                'data' => $transactionData
            ];
        }
    }

    public function payment ($invoice_no)
    {
        $user = auth()->user();
        $transaction = $this->ppobRepository->getTransactionByInvoiceNo($invoice_no);

        if (!isset($transaction)) {
            return [
                'status' => false,
                "message" => trans('error.data_not_found')
            ];
        }

        $inquiryResult = json_decode($transaction->res_inquiry, true);
        $inquiryRequest = json_decode($transaction->req_inquiry, true);

        $inquiryResult['phone'] = $user->phone_code.$user->phone;

        $repo = App::make($inquiryRequest['service']);
        $payment = $repo->setParamsPdamPayment($inquiryResult);

        $updateTableTransactionInfo = [
            'req_payment' => $inquiryResult,
            'res_payment' => $payment['data']
        ];

        if (!$payment['status']) {
            $status = [
                'status' => 1
            ];
            $this->ppobRepository->updateTransactionById($transaction->id, $status);
            $this->ppobRepository->updateTransactionByInvoice($invoice_no, $updateTableTransactionInfo);
            return [
                'status' => false,
                'message' => $payment['message']
            ];
        }

        $update = $this->ppobRepository->updateTransactionByInvoice($invoice_no, $updateTableTransactionInfo);

        return [
            'status' => true,
            'message' => 'ok',
            'data' => $payment['data']
        ];
    }
}
