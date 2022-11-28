<?php

namespace App\Autopayment\Services;

use App\Autopayment\Contracts\ServiceInterface;
use App\Autopayment\Service;
use App\Biller\Http\Entities\DigitalTransaction;

/**
* 
*/
class Pdam extends Service implements ServiceInterface
{
    public function inquiry()
    {
        $schedule = $this->params;
        $product = $this->params->product;
        $user = $this->params->user;

        $customer_phone= $user->phone;
        $customer_id= $schedule->customer_id;
        $product_id= $product->id;

        $service = $product->service()->whereStatus(1)->first();

        if(! $service){
            $this->setErrors(trans('error.service_not_active'), 404);
            return false;
        }

        $order_id = GenerateOrderId('TR');
        
        $params = [
            'customer_phone' => $customer_phone,
            'customer_id' => $customer_id,
            'code' => $service->code,
            'reff_number' => $order_id
        ];
        
        $res = (new $service->switcher->service)->setParams($params)->inquiry();
        
        $admin_fee = $product->admin_fee;
        
        if($res['status'] != 'failed') {
            $base_price = ($res['data']['amount'] + $service->admin_fee + (int) getSettings()->operation_cost);
        
            $transaction = DigitalTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order_id,
                'phone' => $customer_phone,
                'currency' => $product->currency,
                'price' => $res['data']['amount'],
                'admin_fee' => $admin_fee,
                'total' => $res['data']['amount'] + ($admin_fee),
                'base_price' => $base_price,
                'customer_id' => $customer_id,
                'status' => ($res['status'] === 'success')? 'inquiry': 'failed',
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'product_snap' => $product,
                'category' => 'pdam',
                'request_data' => $params,
                'inquiry_data' => $res['inquiry_data'],
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'type' => 1,
                'meta' => [
                    'inquiry' => url('/api/v1/pdam/inquiry'),
                    'payment' => url('/api/v1/pdam/payment')
                ]
            ]);

            return $transaction;
        }

        $errors = isset($res['data']['note'])? $res['data']['note']: trans('error.error_transaction');
        $this->setErrors($errors, 422);
        return false;
    }

    public function payment()
    {
        $tr = $this->params->transaction;
        
        if($tr){
            
            $transaction = $tr;

            $params = [
                'customer_phone' => $transaction->result['customer_phone'],
                'customer_id' => $transaction->result['customer_id'],
                'resi_number' => $transaction->result['resi_number'],
                'amount' => $transaction->result['amount'],
                'reff_number' => $transaction->order_id,
                'code' => $transaction->request_data['code']?? $transaction->code,
                'customer_id1' => $transaction->result['customer_id1'],
                'customer_id2' => $transaction->result['customer_id2'],
                'transaction' => $transaction
            ];

            $res = (new $transaction->service)->setParams($params)->run();
            $transaction->request_data = $params; //$res['request_data']?? $transaction->request_data;
            $transaction->status = $res['status'];
            // $transaction->price = $res['data']['amount'];
            $transaction->response_data = $res['response_data'];
            $transaction->result = array_merge($transaction->result, $res['data']);
            $transaction->save();

            return $transaction;
        }

        $this->setErrors('transaction failed', 422);
        return false;
    }
}