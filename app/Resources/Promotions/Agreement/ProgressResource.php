<?php

namespace App\Resources\Promotions\Agreement;

use Illuminate\Http\Resources\Json\Resource as BaseResource;

class ProgressResource extends BaseResource
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
            'budget' => (int) $this->merchant_fund,
            'used_budget' => (int) $this->used_budget,
            'nusapay_discount' => (float) $this->discount,
            'merchant_discount' => (float) $this->merchant_discount,
            'promotion' => $this->promotions->map(function($m){
                $ini = ['id' => $m->id, 'name' => $m->name, 'summary' => $m->itemsum[0]];
                return $ini;
            }) 
        ];
    }
}
