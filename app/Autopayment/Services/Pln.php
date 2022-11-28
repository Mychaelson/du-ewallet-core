<?php

namespace App\Autopayment\Services;

use App\Autopayment\Contracts\ServiceInterface;
use App\Autopayment\Service;
use App\Biller\Http\Entities\DigitalTransaction;

/**
* 
*/
class Pln extends Service implements ServiceInterface
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
            // 'amount' => (int) $request->input('amount', 0),
            'reff_number' => $order_id
        ];
        
        $res = (new $service->switcher->service)->setParams($params)->inquiry();

        $admin_fee = $product->admin_fee;
        
        $nominal = '';

        if($res['status'] === 'failed'){
            $message = isset($res['data']['note'])? $res['data']['note']: trans('error.error_transaction');
            $this->setErrors($message, 422);
            return false;
        }else{
            $base_price = ($res['data']['amount'] + $service->admin_fee + (int) getSettings()->operation_cost);
            $transaction = DigitalTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order_id,
                'phone' => $user->phone,
                'currency' => $product->currency,
                'price' => $res['data']['amount'],
                'admin_fee' => $admin_fee,
                'total' => $res['data']['amount'] + ($admin_fee),
                'base_price' => $base_price,
                'customer_id' => $schedule->customer_id,
                'status' => ($res['status'] === 'success')? 'inquiry': 'failed',
                'service' => $service->switcher->service,
                'biller_id' => $service->switcher->biller_id,
                'code' => $product->code,
                'product_snap' => array_merge($product->toArray(), ['description' => $product->description . ' ' .$nominal]),
                'category' => 'pln',
                'request_data' => $params,
                'inquiry_data' => $res['inquiry_data'],
                'result' => $res['data'],
                'response_data' => $res['response_data'],
                'type' => 1,
                'meta' => [
                    'inquiry' => url('/api/v1/pln/inquiry'),
                    'payment' => url('/api/v1/pln/payment')
                ]
            ]);

            return $transaction;
        }
    }

    public function payment()
    {
        $tr = $this->params->transaction;
        
        if($tr){

            $transaction = DigitalTransaction::where('order_id', $tr->order_id)->firstOrFail();
            
            $params = [
                'customer_phone' => $transaction->result['customer_phone'],
                'customer_id' => $transaction->customer_id,
                'resi_number' => $transaction->result['resi_number'],
                'code' => $transaction->request_data['code'],
                'amount' => (int) $transaction->result['amount'],
                'reff_number' => $transaction->order_id,
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
        $this->setErrors('inquiry not found', 404);
        return false;
    }
}