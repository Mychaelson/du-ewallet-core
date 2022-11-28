<?php

namespace App\Repositories\Ppob\EVoucher;

use App\Models\Ppob\DigitalProducts;
use App\Models\Ppob\DigitalTransactions;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Repositories\Ppob\Vendor\Service\PortalPulsa;

class EVoucherRepository
{

    public function inquiry($request,$user_id)
    {

        $product = DigitalProducts::where('code', $request->code)->first();
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

        $service = $product->service()->where('service_id',$request->service_id)->active()->first();
        if(!$service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
       
        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
       
            
        $order_id = GenerateOrderId('TR');
        $phone = cleanphone($request->customer_phone);
        $params = [
            'customer_phone' => $phone,
            'code' => $service->code,
            'amount' => $service->base_price,
            'reff_number' => $order_id
        ];

        // call vendor/provider service
        $res = (new $service->switcher->service)->setParams($params)->inquiry();
        // $res = (new PortalPulsa)->setParams($params)->inquiry();
        
        $admin_fee = $product->admin_fee;
        $price = $product->price;
        $operation_cost = (int) getSettings('operation_cost')->first()->value;
        $base_price = (($price + $admin_fee) +  $operation_cost);

        if($res['status'] != 'failed') {
            $param = [
                'order_id' => $order_id,
                'phone' => $phone,
                'currency' => $product->currency,
                'user_id' => $user_id,
                'price' => (double) $price,
                'admin_fee' => (double) $admin_fee,
                'total' => (double) $price + $admin_fee,
                'base_price' => $base_price,
                'customer_id' => $request->customer_id ?? $phone,
                'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'product_snap' => $product->toArray(),
                'category' => 'evoucher',
                'request_data' => $params,
                'inquiry_data' => $res['inquiry_data'] ?? [],
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'type' => 1,
                'meta' => [
                    'inquiry' => 'App\Repositories\Ppob\EVoucher\EVoucherRepository',
                    // 'payment' => url('/api/evoucher/payment')
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

        }else{
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
    }

}
