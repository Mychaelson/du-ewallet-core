<?php

namespace App\Repositories\Ppob\Subscription;

use App\Models\Ppob\DigitalProducts;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Models\Ppob\DigitalTransactions;

class SubscriptionRepository
{
    public function inquiry($request,$user_id)
    {
        $user = auth('api')->user();
        $product = DigitalProducts::find($request->product_id);
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

            
        $service = $product->service()->where('service_id',$request->service_id)->active()->first();
        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
            
        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
        

        $order_id = GenerateOrderId('TR');
        $params = [
            'customer_id' => $request->customer_id,
            'code' => $service->code,
            'action' => 'subscription'
        ];
        
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
                'phone' => $user->phone,
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
                'product_snap' => $product->toArray(),
                'category' => 'subscription',
                'request_data' => $params,
                'inquiry_data' => $res['inquiry_data'] ?? [],
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'type' => 1,
                'meta' => [
                    'inquiry' => 'App\Repositories\Ppob\Subscription\SubscriptionRepository',
                    // 'payment' => url('/api/subscription/payment')
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
}
