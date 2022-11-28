<?php

namespace App\Resources\Ppob\Data;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 

class DataResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'user_id' => (int) $this->user_id,
            'label_id' => (int) $this->label_id,
            'order_id' => (string) $this->order_id,
            'phone' => (string) $this->phone,
            'currency' => (string) $this->currency,
            'price' => (double) $this->price,
            'admin_fee' => (double) $this->admin_fee,
            'discount_amount' => (double) $this->discount_amount,
            'voucher_amount' => (double) $this->voucher_amount,
            'ncash' => (double) $this->ncash,
            'total' => (double) $this->total,
            'status' => (string) $this->status,
            'service' => (string) $this->service,
            'biller_id' => (int) $this->biller_id,
            'product' => (object) json_decode($this->product_snap),
            'result' => (object) json_decode($this->result),
            'meta' => (object) json_decode($this->meta),

            'payment_channel' => (string) $this->payment_channel,
            'payment_information' => (object) json_decode($this->payment_information),

            'uuid' => (string) $this->uuid,
            'updated_at' => (string) Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'created_at' => (string) Carbon::parse($this->created_at)->format('Y-m-d H:i:s')
        ];
    }

    
}
