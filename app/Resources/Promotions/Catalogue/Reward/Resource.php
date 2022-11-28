<?php

namespace App\Resources\Promotions\Catalogue\Reward;

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
            'catalogue_name' => $this->cataloguesimple->name,
            'merchant' => (int) $this->merchant,
            'value' => (int) $this->value,
            // 'barcode' => (float) $this->barcode,
            'code' => (string) $this->code,
            'user_id' => (int) $this->user_id,
            'status' => 1,
            'status_text' => 'success',
            'is_voucher' =>  NULL!=$this->code ? TRUE : FALSE,
            'expired_at' => (string) $this->expired_at,
            'released_at' => $this->released_at ,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            // 'product' => (object) $this->product,
            'merchant' => (object) $this->merchantsimple,
            'catalogue' => $this->cataloguesimple,
        ];
    }

    private function setReleasedAt(?string $data)
    {
        return $data;
    }
}
