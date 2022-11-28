<?php

namespace App\Resources\Promotions\MerchantStamp;

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
            'activity' => 'merchant-stamp-screen',
            'image' => (is_array($this->images)) ? (string) current($this->images) : null,
            'info' => (string) $this->merchantsimple->name ?? '',
            'title' => (string) $this->name,
            'type' => (string) 'stamp',
            'amount' => (int) $this->stamp_required,
            'stamp_required' => (int) $this->stamp_required,
        ];
    }
}
