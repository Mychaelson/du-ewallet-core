<?php

namespace App\Resources\Promotions\Point;

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
            'promotion_id' => (int) $this->promotion_id,
            'promotion_name' => (string) $this->promotion_name,
            'filter_id' => (int) $this->filter_id,
            'merchant_id' => (string) $this->merchant_id,
            'filter_type' => (string) $this->filter_type,
            'filter_name' => (string) $this->filter_name,
            'filter_slug' => (string) $this->filter_slug,
            'filter_amount' => (int) $this->filter_amount,
            'filter_operator' => (string) $this->filter_operator,
            'filter_compareto' => (string) $this->filter_compareto,
            'filter_compare' => (string) $this->filter_compare
        ];
    }
}
