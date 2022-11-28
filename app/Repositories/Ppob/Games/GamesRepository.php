<?php

namespace App\Repositories\Ppob\Games;

use App\Models\Ppob\DigitalCategories;
use App\Models\Ppob\DigitalProducts;
use App\Models\Ppob\DigitalTransactions;
use App\Repositories\Payment\BillRepository;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;
use App\Resources\Ppob\CategoryProduct\CategoryProductResource as ResultResource;
use App\Resources\Ppob\Data\DataResource as DataResource;
use App\Repositories\Ppob\Vendor\Service\PortalPulsa;
use App\Repositories\Ppob\Base\PpobRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class GamesRepository
{
    private $ppobRepository, $billRepository;
    public function __construct(
        PpobRepository $ppobRepository,
        BillRepository $billRepository
    ){
        $this->ppobRepository = $ppobRepository;
        $this->billRepository = $billRepository;
    }
    public function getDetail($request,$slug)
    {
            $category = DigitalCategories::with([
                'products' => function($q){
                    $q->where('status',1);
                }])->where('slug',$slug)->orWhere('id',$slug)->firstOrFail();

            $extra = [];
            $products = [];
            if($category->type == "unipin"){
                // Skip karena blm dapet credential unipim
                // $res = (new FlashTopUp)->gameDetail($category->meta['game_code']);
                
            }

            return response()->json([
                'success'=> true,
                'response_code' => 200,
                'data' => new ResultResource($category),
                'meta' => [
                    'extra' => $extra,
                    'products' => $products
                ]
            ], 200);
    }

    public function addOrder ($data)
    {
        $productCode = $data['product_code'];
        $user_id = $data['user_id'];
        $invoice_no = $data['reff_no'];
        $productInfo = $this->ppobRepository->findProductByProductCode($productCode);

        if (!isset($productInfo)) {
            return [
                'status' => false,
                'message' => trans('messages.product-not-found', ['code' => $productCode])
            ];
        }

        if (!$productInfo->status) {
            return [ 
                'status' => false,
                'message' => trans('error.service_not_active')
            ];
        }

        $transactionCountToday = $this->ppobRepository->countTransaction($productCode, $user_id);

        $inquiryInfo = [
            'idcust' => $data['idcust'],
            'phone' => $data['phone'],
            'product_code' => $productInfo->service_product_code,
            'reff_number' => $invoice_no,
            'no' => intval($transactionCountToday) + 1,
            'service' => $productInfo->service_path
        ];

        DB::beginTransaction();
        try {

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
            $transactionData = $this->ppobRepository->getTransactionById($transaction, 'games');

            $createBill = $this->billRepository->createTransactionBill($invoice_no, 'App\Repositories\Ppob\Games\GamesRepository');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'status' => true,
            'message' => trans('messages.inquiry-success', ['product' => $productInfo->name]),
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
        $inquiry = $repo->setParamsGames($inquiryInfo);
        
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

//    public function inquiry($request,$user_id)
//    {
//     $product = DigitalProducts::find($request->product_id);
//     if(! $product)
//         return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

//     $service = $product->service()->active()->first();

//     if(! $service)
//         return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
    
//     if(!$service->switcher)
//         return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
    
//     $order_id = isset($request->reff_number) ? $request->reff_number : GenerateOrderId('TR');
//     $phone = cleanphone($request->customer_phone);
//     $params = [
//         'customer_phone' => $phone,
//         'code' => $service->code,
//         'reff_number' => $order_id,
//         'extra' => $request->input('extra', false),
//         'action' => 'games'
//     ];

//     // call vendor/provider service
//     \Log::debug('PDAM-repo :: getRequest '.json_encode($params) );
//     $res = (new $service->switcher->service)->setParams($params)->inquiry();
//     \Log::debug('PDAM-repo :: getResponse '.json_encode($res));
    
//     $admin_fee = $product->admin_fee;
//     $price = $product->price;
//     $base_price = 0;
//     if($res['success']){

//         $result = $res['data']?? [
//             'customer_phone' => $phone, 
//             'serial_number' => '', 
//             'provider'=> $product->provider
//         ];
//         $result = array_merge($result, ['provider'=> $product->provider]);
        
//         $param = [
//             'order_id' => $order_id,
//             'phone' => $phone,
//             'currency' => $product->currency,
//             'user_id' => $user_id,
//             'price' => (double) $price,
//             'admin_fee' => (double) $admin_fee,
//             'total' => (double) $price + $admin_fee,
//             'base_price' => $base_price,
//             'customer_id' => $phone,
//             'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
//             'service' => $service->switcher->service,
//             'biller_id' => $service->switcher->biller_id,
//             'code' => $product->code,
//             'product_snap' => $product->toArray(),
//             'category' => 'games',
//             'request_data' => array_merge($params, $result),
//             'inquiry_data' => $res['inquiry_data']?? [],
//             'result' => $result,
//             'type' => 0,
//             'meta' => [
//                 'inquiry' => 'App\Repositories\Ppob\Games\GamesRepository',
//                 // 'payment' => url('/api/games/payment'),
//                 'services' => [$service->id]
//             ],
//             'payment_information' => []
//         ];

//         // insert to DB
//         $createID = $this->ppobRepository->insertIdDigitalTransaksi($param);
//         $transaction = $this->ppobRepository->getDetail($createID);
//         \Log::info('PDAM-repo :: insert DT Aman');

//         //  if(isset($request->reff_number)){
//         //         $param = [
//         //             'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
//         //             'category' => 'pdam',
//         //             'request_data' => $params,
//         //             'inquiry_data' => $res['inquiry_data'] ?? [],
//         //             'result' => $res['data'],
//         //             'response_data' => $res['response_data'],
//         //             'type' => 1,//1 inquiry ,0 create-add 
//         //             'updated_at' => now()
//         //         ];
    
//         //         // udpate to DB
//         //         $ppobid = $this->ppobRepository->updateDigitalTransaksi($order_id,$param);
//         //         $transaction = $this->ppobRepository->getDetailByOrder_id($order_id);
//         //         \Log::info('PDAM-repo :: update DT Aman');

//         //     }

//         return response()->json([
//             'success' => true,
//             'response_code' => 200,
//             'data' => new DataResource($transaction),
//             'meta' => [
//                 'execution_time' => 0
//             ]
//         ], 200); 
//     }

//     $message = $res['data']['note']?? trans('error.data_not_found');
    
//     return response()->json([
//         'success' => false,
//         'response_code' => 422,
//         'message' => $message
//     ], 422);
//    }
}
