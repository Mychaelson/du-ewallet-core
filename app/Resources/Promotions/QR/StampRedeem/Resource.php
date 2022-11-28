<?php

namespace App\Resources\Promotions\QR\StampRedeem;

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
        $data = [
            "id" => $this->id,
            "user_id" => $this->user_id ,
            // "value" => $this->value ,
            "barcode" => $this->barcode ,
            // "product" => $this->product ,
            "transaction_id" => $this->transaction_id ,
            "transaction_id" => $this->transaction_id ,
            // "type" => $this->type ,
            "redeemed_at" => (null!=$this->redeemed_at)? (string) $this->redeemed_at : null,
            "released_at" => (null!=$this->released_at)? (string) $this->released_at : null ,
            "expired_at" => (string)$this->expired_at ,
            "catalogue" => $this->cataloguesimple,
            "merchant" => $this->cataloguesimple->merchant,
        ];

        $this->cataloguesimple->image = is_array($this->cataloguesimple->images) ? $this->cataloguesimple->images[0] : '';
        return $data;
    }
}
