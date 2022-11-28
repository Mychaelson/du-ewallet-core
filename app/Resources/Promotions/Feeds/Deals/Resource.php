<?php

namespace App\Resources\Promotions\Feeds\Deals;

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
            'title' => (string) $this->product_name,
            'info' => (string) $this->merchant->name,
            'type' => (string) (4==$this->promotion_type) ? 'point' : 'stamp',
            'amount' => (int) $this->exchange_amount,
            // 'exchange_extra_amount' => (int) $this->exchange_extra_amount,
            'description' => (string) $this->description,
            'image' => (is_array($this->images)) ? (string) current($this->images) : null,
            'action' => (string) 'activity',
            'action_destiny' => (string) 'voucher-detail',
        ];
    }
}
