<?php

namespace App\Resources\Promotions\Agreement;

use Illuminate\Http\Resources\Json\Resource as BaseResource;

class DetailResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $properties = [];
        // if(is_array($this->properties)){
        //     $properties = array_map(function($v){
        //         return (int) $v;
        //     }, $this->properties);
        // }
        
        return [
            'id' => (int) $this->id,
            'document_name' => (string) $this->document_name,
            'document_number' => (string) $this->document_number,
            'description' => (string) $this->description,
            'used_budget' => (int) $this->used_budget,
            'nusapay_discount' => (float) $this->discount,
            'merchant_discount' => (float) $this->merchant_discount,
            'promotion' => $this->promotion->name,
            'promotion_info' => [
                'transaction_count' => (int) $this->redemption->transaction ?? 0,
                'nusapay_discount' => (double) ($this->discount * $this->redemption->transaction_value) ?? 0,
                'merchant_discount' => (double) ( $this->redemption->transaction_value - ($this->discount * $this->redemption->transaction_value) ) ?? 0,
                'total_discount' => $this->redemption->transaction_value ?? 0, 
            ],
        ];
    }
}
