<?php

namespace App\Repositories\Ppob\Gopay;

use App\Resources\Ppob\Data\DataResource as DataResource;
use App\Repositories\Ppob\Vendor\Service\PortalPulsa;
use App\Models\Ppob\DigitalProducts;


class GopayRepository
{
   public function topUp($request,$user_id)
   {
        
        $product = DigitalProducts::whereCode('GOPAY')->get();
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.product_not_active')], 404);


        dd($product);
        $service = $product->service()->where('service_id',$request->service_id)->active()->first();
        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
            
        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);
        
        $order_id = GenerateOrderId('TR');
        $phone = cleanphone($request->customer_phone);
        $price = $request->amount;
        $params = [
            'customer_phone' => $phone,
            'amount' => $price,
            'reff_number' => $order_id
        ];
        
        // call vendor/provider service
        $res = (new $service->switcher->service)->setParams($params)->run();
        dd($res);
        $admin_fee = $product->admin_fee;

        if($res['status'] != 'failed') {
            $param = [
                'order_id' => $order_id,
                'phone' => $phone,
                'currency' => $product->currency,
                'user_id' => $user_id,
                'price' => $price,
                'admin_fee' => $admin_fee,
                'total' => $price + $admin_fee,
                'customer_id' => $phone,
                'status' => $res['status'],
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id, 
                'code' => $product->code,
                'product_snap' => $product,
                'category' => 'e-money',
                'request_data' => $params,
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'meta' => [
                    'payment' => url('/api/gopay/topup')
                ],
                'payment_information' => []
            ];
            
            // insert to DB
            $transaction = DigitalTransaction::create($param);
            event(new PurchaseSuccessEvent($transaction, $request));
            
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'data' => new DataResource($transaction),
                'meta' => [
                    'execution_time' => 0
                ]
            ], 200);
        }
        
        event(new PurchaseFailedEvent($transaction, $request));
        return response()->json([
            'success' => false,
            'response_code' => 422,
            'message' => trans('error.error_transaction')
        ], 422);
   }
}
