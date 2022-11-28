<?php

namespace App\Repositories\Ppob\Coin;

use App\Models\Ppob\DigitalProducts;
use App\Models\Ppob\DigitalTransactions;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Repositories\Ppob\Vendor\Service\PortalPulsa;

class CoinRepository
{

    public function inquiry($request,$user_id)
    {
        $product = DigitalProducts::where('slug',$request->product_id)->orWhere('id',$request->product_id)->first();
        if(! $product)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

        $service = $product->service()->where('service_id',$request->service_id)->active()->first();
        if(! $service)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);

        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_found')], 404);

        if(!$service->switcher)
            return response()->json(['success' => false, 'response_code' => 404, 'message' => trans('error.service_not_active')], 404);
        
        $order_id = GenerateOrderId('TR');
        $price = $product->price;
        $phone = $request->customer_phone;
        $base_price = $request->amount;
        
        $params = [
            'customer_phone' => $phone,
            'code' => $service->code,
            'reff_number' => $order_id,
            'amount' => $request->amount,
            'currency' => $product->currency,
            'admin_fee' => $product->admin_fee,
        ];

        // call vendor/provider service
        $res = (new $service->switcher->service)->setParams($params)->inquiry();
        // $res = (new PortalPulsa)->setParams($params)->inquiry();
        
        $price = $product->price;
        $admin_fee = $product->admin_fee;
        if($res['success']){
            $result = $res['data'];
            
             $param = [
                'order_id' => $order_id,
                'phone' => $phone,
                'currency' => $product->currency,
                'user_id' => $user_id,
                'price' => (double) $price,
                'admin_fee' => (double) $admin_fee,
                'total' => (double) $price + $admin_fee,
                'base_price' => $base_price,
                'customer_id' => $phone,
                'status' => ($res['status'] === 'success')? 'inquiry': (($res['status'] === 'pending')? 'inq-pending': 'failed'),
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'product_snap' => $product->toArray(),
                'category' => 'coin',
                'request_data' => array_merge($params, $result),
                'inquiry_data' => $res['inquiry_data']?? [],
                'result' => $result,
                'response_data' => $res['response_data'],
                'type' => 0,
                'meta' => [
                    'inquiry' => 'App\Repositories\Ppob\Coin\CoinRepository',
                    // 'payment' => url('/api/games/payment'),
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

        $message = $res['data']['note']?? "Not record found";
        
        return response()->json([
            'success' => false,
            'response_code' => 422,
            'message' => $message
        ], 422);

    }

}
