<?php

namespace App\Resources\Promotions\Catalogue\Product;

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
            'id' => (int) $this->id,
            'merchant' => $this->merchantsimple,
            'product_name' => (string) $this->name,
            'product_slug' => (string) $this->slug,
            'type' => $this->type,
            'type_text' => (string) (4==$this->type) ? 'point' : 'coupon',
            'exchange_amount' => (int) $this->exchange,
            'exchange_extra_amount' => (int) $this->exchange_extra,
            'description' => (string) $this->description,
            'images' => (array) $this->images,
            'available' => (int)  ($this->quantity - $this->quantity_exchanged),
        ];

        ($this->linked_promotion) ? $data['promotion'] = $this->stampPromotion : false;

        return $data;
    }
}
