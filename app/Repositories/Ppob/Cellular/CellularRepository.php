<?php

namespace App\Repositories\Ppob\Cellular;

use App\Models\Ppob\Categories;
use App\Models\Ppob\DigitalProducts;
use App\Models\Ppob\DigitalTransactions;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Repositories\Ppob\Vendor\Service\PortalPulsa;
use App\Repositories\Wallet\WalletsRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Wallet\WalletsTransactionsRepository;
use App\Repositories\Ppob\Base\PpobRepository;
use App\Repositories\Payment\BillRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CellularRepository
{
    private $category;
    private $products;
    private $usersRepository;
    private $walletsRepository;
    private $walletsTransactionsRepository;
    private $ppobRepository;
    private $billRepository;

    public function __construct(
        Categories $category,DigitalProducts $products ,UsersRepository $usersRepository,WalletsRepository $walletsRepository
        ,WalletsTransactionsRepository $walletsTransactionsRepository, PpobRepository $ppobRepository, BillRepository $billRepository
    ){
        $this->category = $category;
        $this->products = $products;
        $this->usersRepository = $usersRepository;
        $this->walletsRepository = $walletsRepository;
        $this->walletsTransactionsRepository = $walletsTransactionsRepository;
        $this->ppobRepository = $ppobRepository;
        $this->billRepository = $billRepository;
    }

    public function getData($cat,$currency,$ip)
    {

        $category = $this->category->where('slug', $cat)->firstOrFail();
        $subCategory = $category->childs()->active();

        if($subCategory && $subCategory->count() > 0){
            $data = $subCategory->with(['products' => function($q) use ($currency){ 
                $q->where('currency', $currency)->where('status', 1)->orderBy('order');
            }])->get();

            return (new Resultcollection($data))
                ->response()
                ->header('X-Ip-Request', $ip);
        }else{
            return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.data_not_found')], 404);
        }
    }

    public function getDataV2($cat,$currency = null,$ip)
    {

        $category = $this->category->where('slug', $cat)->firstOrFail();
        $subCategory = $category->childs()->active();
        if($subCategory && $subCategory->count() > 0){
            $data = $subCategory->with(['products' => function($q){ 
                $q->where('status', 1)->orderBy('price_sell');
            }])->get();

            return (new Resultcollection($data))
                ->response()
                ->header('X-Ip-Request', $ip);
        }else{
            return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.data_not_found')], 404);
        }
    }

    public function addOrder($request,$user)
    {

        $product = $this->products->find($request->product_id);
        $service = $product->service()->active()->first();
        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

        DB::beginTransaction();
        try {
            $order_id = GenerateOrderId('TR');
            $price = $product->price;
            $phone = $request->customer_phone;
            $admin_fee = $product->admin_fee;
            $total = $price + $admin_fee;
            $params = [
                'customer_phone' => cleanphone($phone),
                'danom' => $product->danom,
                'code' => $service->code,
                'reff_number' => $order_id
            ];

            $base_price = 0;

            $param = [
                'order_id' => $order_id,
                'phone' => $phone,
                'currency' => $product->currency,
                'user_id' => $user,
                'price' => (double) $price,
                'admin_fee' => (double) $admin_fee,
                'total' => (double) $total,
                'base_price' => (double) $base_price,
                'customer_id' => $phone,
                'status' => 'inquiry',
                'label_id' => 1,//join to wallet 1 = cash out
                'service' => $service->switcher->service, 
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'product_snap' => $product,
                'category' => 'pulsa',
                'request_data' => json_encode($params),
                'type' => 0,//1 inquiry ,0 create-add 
                'result' => json_encode([
                    'customer_phone' => $phone,
                    'serial_number' => '',
                    'provider'=> $product->provider
                ]),
                'meta' => json_encode([
                    'inquiry' => 'App\Repositories\Ppob\Cellular\CellularRepository',
                    // 'payment' => url('/api/cellular/payment'),
                    'services' => [$service->id]
                ]),
                'payment_information' => '[]',
                'created_at' => now(),
                'updated_at' => now()
            ];

            // insert to DB
            $createID = $this->ppobRepository->insertIdDigitalTransaksi($param);
            $transaction = $this->ppobRepository->getDetail($createID);

            \Log::info('Cellular-add-order :: insert DT Aman');

            $this->billRepository->createBill($order_id);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::info('Cellular-add-order :: gagal jadi di roll-back : ');
            return $e->getMessage();
        }
            

        return response()->json([
            'success' => true,
            'response_code' => 200,
            'data' => new ResultResource($transaction),
            'meta' => [
                'execution_time' => 0
            ]
        ], 200);
    }

    public function inquiry($request,$user)
    {
        $product = $this->products->where('code' ,$request->product_id)->first();
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

        $service = $product->service()->active()->first();
        if(isset($request->service_id)){
            $service->where('service_id',$request->service_id)->first();
        }

        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
            
        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
        
        $order_id = isset($request->reff_number) ? $request->reff_number : GenerateOrderId('TR');
        $phone = cleanphone($request->customer_phone);
        $params = [
            'customer_phone' => $phone,
            'danom' => $product->danom,
            'code' => $service->code,
            'reff_number' => $order_id,
        ];
        // call vendor/provider service
        \Log::debug('Cellular-repo :: getRequest '.json_encode($params) );
        $res = (new $service->switcher->service)->setParams($params)->inquiry();
        
        \Log::debug('Cellular-repo :: getResponse '.json_encode($res));
        $admin_fee = $product->admin_fee;
        $price = $product->price;
        $operation_cost = (int) getSettings('operation_cost')->first()->value;
        $base_price = (($price + $admin_fee) + (int) $operation_cost);
        $total = $price + $admin_fee;

        
        
        if($res['status'] != 'failed') {
            
                $param = [
                    'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
                    'category' => 'cell-postpaid',
                    'request_data' => $params,
                    'inquiry_data' => $res['inquiry_data'] ?? [],
                    'result' => $res['data'],
                    'response_data' => $res['response_data'],
                    'type' => 1,//1 inquiry ,0 create-add 
                    'updated_at' => now()
                ];
    
                // insert to DB
                $ppobid = $this->ppobRepository->updateDigitalTransaksi($order_id,$param);
                $transaction = $this->ppobRepository->getDetailByOrder_id($order_id);
                \Log::info('Cellular-add-order :: update DT Aman');


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

    public function addOrderV2 ($data)
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
            $transactionData = $this->ppobRepository->getTransactionById($transaction, 'cellular');

            $createBill = $this->billRepository->createTransactionBill($invoice_no, 'App\Repositories\Ppob\Cellular\CellularRepository');

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

    public function payment ($invoice_no){
        $transaction = $this->ppobRepository->getTransactionByInvoiceNo($invoice_no);

        if (!isset($transaction)) {
            return [
                'status' => false,
                "message" => trans('error.data_not_found')
            ];
        }

        $inquiryInfo = json_decode($transaction->req_inquiry, true);
        $repo = App::make($inquiryInfo['service']);
        $inquiry = $repo->setParamsTopupPulsa($inquiryInfo);

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
