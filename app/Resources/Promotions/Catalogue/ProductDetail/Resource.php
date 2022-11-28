<?php

namespace App\Resources\Promotions\Catalogue\ProductDetail;

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
        $properties = [];
        // if(is_array($this->properties)){
        //     $properties = array_map(function($v){
        //         return (int) $v;
        //     }, $this->properties);
        // }
        
        return [
            'id' => (int) $this->id,
            // 'promotion_id' => (int) $this->promotion_id,
            'category' => (int) $this->category,
            // 'promotion_type' => (int) $this->promotion_type,
            'type' => (string) 'point',
            'product_name' => (string) $this->name,
            'product_slug' => (string) $this->slug,
            'exchange_amount' => (int) $this->exchange,
            'exchange_extra_amount' => (int) $this->exchange_extra,
            'description' => (string) $this->description,
            'image' => (is_array($this->images)) ? (string) current($this->images) : null,
            'images' => (is_array($this->images)) ? (array) $this->images : [],
            'product_value' => (int) $this->value,
            // 'voucher_code' => (string) $this->voucher_code,
            'applied_merchant' => (int) $this->merchant,
            'exchange_quantity' => (int) $this->quantity,
            'exchanged_count' => (int) $this->quantity_exchanged,
            'available' => (int)  ($this->quantity_exchanged - $this->quantity),
            'meta' => [
                'merchant' => $this->merchantsimple,
                'highlight' => [
                    'title' => trans('messages.general.promotion.voucher_highlight.title'),
                    'content' => trans('messages.general.promotion.voucher_highlight.content', ['product' => $this->name]),
                ],
                'contact' => [
                    'title' => trans('messages.general.promotion.voucher_contact.title'),
                    'content' => trans('messages.general.promotion.voucher_contact.content', ['merchant' => ($this->merch()->name ?? '')]),
                ],
                'terms' => [
                    'title' => trans('messages.general.promotion.tnc.title'),
                    'content' => (string) $this->terms
                ],
            ],
        ];
    }
    private function merch()
    {
        return $this->appliedMerchant ?? $this->merchant;
    }
}
