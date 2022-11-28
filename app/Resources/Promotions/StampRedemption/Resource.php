<?php

namespace App\Resources\Promotions\StampRedemption;

use Illuminate\Http\Resources\Json\Resource as BaseResource;

class Resource extends BaseResource
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
            'id' =>  $this->id,
            'catalogue_id' => (int) $this->catalogue_id,
            'user_id' => (int) $this->user_id,
            'barcode' => (int) $this->barcode,
            'transaction_id' => (string) $this->transaction_id,
            'redeemed_at' => (string) $this->redeemed_at,
            'expired_at' => null!=$this->expired_at?(string)$this->expired_at:null,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
