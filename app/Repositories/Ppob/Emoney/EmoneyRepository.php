<?php

namespace App\Repositories\Ppob\Emoney;

use App\Models\Ppob\DigitalProducts;
use App\Models\Ppob\DigitalTransactions;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;
use App\Resources\Ppob\Data\DataResource as ResultResource;

class EmoneyRepository
{

    public function addOrder($request,$user_id)
    {
        $product = DigitalProducts::find($request->product_id);
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
        
        $service = $product->service()->active()->first();
        
        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
        
        $order_id = GenerateOrderId('TR');
        $price = $product->price;
        $phone = cleanphone($request->customer_phone);
        $admin_fee = $product->admin_fee;
        $params = [
            'customer_phone' => $phone,
            'danom' => $product->danom,
            'code' => $service->code,
            'reff_number' => $order_id
        ];
        $operation_cost = getSettings('operation_cost')->first()->value;

        $param = [
            'order_id' => $order_id,
            'phone' => $phone,
            'currency' => $product->currency,
            'user_id' => $user_id,
            'price' => (double) $price,
            'admin_fee' => (double) $admin_fee,
            'total' => (double) $price + $admin_fee,
            'base_price' => ($service->base_price + $service->admin_fee + (int) $operation_cost),
            'customer_id' => $phone,
            'status' => 'inquiry',
            'service' => $service->switcher->service,
            'biller_id' => $service->switcher->biller_id,
            'code' => $product->code,
            'product_snap' => $product->toArray(),
            'category' => 'emoney',
            'request_data' => $params,
            'type' => 0,
            'result' => [
                'customer_phone' => $phone,
                'serial_number' => '',
                'provider'=> $product->provider
            ],
            'meta' => [
                'inquiry' => url('/api/emoney/add-order'),
                'payment' => url('/api/emoney/payment'),
                'services' => [$service->id]
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

}
