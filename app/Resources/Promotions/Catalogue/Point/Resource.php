<?php

namespace App\Resources\Promotions\Catalogue\Point;

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
            'merchant_id' => (string) $this->merchant_id,
            'promotion_name' => (string) $this->promotion_name,
            'description' => (string) $this->description,
            'used_budget' => (int) $this->used_budget,
        ];
    }
}
