<?php

namespace App\Autopayment\Services;

use App\Autopayment\Contracts\ServiceInterface;
use App\Autopayment\Service;
use App\Biller\Http\Entities\DigitalTransaction;

/**
* 
*/
class Pulsa extends Service implements ServiceInterface
{
    public function inquiry()
    {
        $schedule = $this->params;
        $product = $this->params->product;
        $user = $this->params->user;

        $customer_phone= $schedule->customer_id;
        $customer_id= $schedule->customer_id;
        $product_id= $product->id;

        $service = $product->service()->whereStatus(1)->first();

        if(! $service){
            $this->setErrors(trans('error.service_not_active'), 404);
            return false;
        }

        $order_id = GenerateOrderId('TR');
        
        $params = [
            'customer_phone' => cleanphone($customer_phone),
            'danom' => $product->danom,
            'code' => $service->code,
            'reff_number' => $order_id
        ];
        
        $base_price = 0;
        $price = $product->price;
        $transaction = DigitalTransaction::create([
            'user_id' => $user->id,
            'order_id' => $order_id,
            'phone' => $customer_phone,
            'currency' => $product->currency,
            'price' => (double) $price,
            'admin_fee' => (double) $product->admin_fee,
            'total' => (double) $price + $product->admin_fee,
            'base_price' => (double) $base_price,
            'status' => 'inquiry',
            'service' => $service->switcher->service, 
            'biller_id' => $service->switcher->biller_id,
            'code' => $product->code,
            'product_snap' => $product,
            'category' => 'pulsa',
            'request_data' => $params,
            'type' => 0,
            'result' => [
                'customer_phone' => $customer_phone,
                'serial_number' => '',
                'provider'=> $product->provider
            ],
            'meta' => [
                // 'inquiry' => url('/api/v1/pulsa/add-order'),
                // 'payment' => url('/api/v1/pulsa/payment'),
                'services' => [$service->id]
            ]
        ]);

        return $transaction;
    }

    public function payment()
    {
        $tr = $this->params->transaction;
        
        if($tr){

            $transaction = $tr;
            
            $res = (new $transaction->service)->setParams($transaction->request_data)->run();
            
            $base_price = 0;

            if(in_array($res['status'], ['success', 'pending'])) {
                $base_price = isset($res['base_price'])? ($res['base_price']  + (int) getSettings()->operation_cost): $transaction->base_price;
            }

            $transaction->fill([
                'order_id' => $transaction->order_id,
                'status' => $res['status'],
                'result' => array_merge($res['data'], ['provider' => $transaction->result['provider']]),
                'response_data' => $res['response_data'],
                'base_price' => $base_price
            ]);

            $transaction->save();

            return $transaction;
        }

        $this->setErrors('transaction failed', 422);

        return false;
    }
}