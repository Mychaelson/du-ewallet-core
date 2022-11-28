<?php

namespace App\Autopayment\Services;

use App\Autopayment\Contracts\ServiceInterface;
use App\Autopayment\Service;
use App\Biller\Http\Entities\DigitalTransaction;

/**
* 
*/
class Bpjs extends Service implements ServiceInterface
{
    public function inquiry()
    {
        $schedule = $this->params;
        $product = $this->params->product;
        $user = $this->params->user;

        $service = $product->service()->whereStatus(1)->first();

        if(! $service){
            $this->setErrors(trans('error.service_not_active'), 404);
            return false;
        }

        $order_id = GenerateOrderId('TR');
        
        $params = [
            'customer_phone' => $user->phone,
            'customer_id' => $schedule->customer_id,
            'code' => $service->code,
            'month' => 1,
            'reff_number' => $order_id,
            'admin_fee' => $service->admin_fee,
        ];
        
        $res = (new $service->switcher->service)->setParams($params)->inquiry();
        
        $admin_fee = $product->admin_fee;
        
        if($res['status'] === 'failed'){

            $message = isset($res['data']['note'])? $res['data']['note']: trans('error.error_transaction');
            $this->setErrors($message, 422);
            return false;

        }else{

            $base_price = ($res['data']['amount'] + $service->admin_fee + (int) getSettings()->operation_cost);
            $transaction = DigitalTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order_id,
                'currency' => $product->currency,
                'phone' => $user->phone,
                'price' => $res['data']['amount'],
                'admin_fee' => $admin_fee,
                'total' => $res['data']['amount'] + ($admin_fee),
                'base_price' => $base_price,
                'customer_id' => $schedule->customer_id,
                'status' => ($res['status'] === 'success')? 'inquiry': 'failed',
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'category' => 'bpjs',
                'product_snap' => $product,
                'request_data' => $params,
                'inquiry_data' => $res['inquiry_data'],
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'type' => 1,
                'meta' => [
                    'inquiry' => url('/api/v1/bpjs/inquiry'),
                    'payment' => url('/api/v1/bpjs/payment')
                ]
            ]);

            return $transaction;
        }
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
                'month' => $transaction->request_data['month']?? $transaction->result['bill_quantity'],
                'amount' => $transaction->result['amount'],
                'reff_number' => $transaction->order_id,
                'code' => $transaction->request_data['code']?? $transaction->code,
                'transaction' => $transaction
            ];

            $res = (new $transaction->service)->setParams($params)->run();

            $transaction->status = $res['status'];
            $transaction->price = $res['data']['amount'];
            $transaction->response_data = $res['response_data'];
            $transaction->result = array_merge($transaction->result, $res['data']);
            $transaction->save();

            return $transaction;
        }

        $this->setErrors('transaction failed', 422);
        return false;
    }
}