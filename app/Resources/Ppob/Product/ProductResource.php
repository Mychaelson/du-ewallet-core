<?php

namespace App\Resources\Ppob\Product;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
// use App\Repositories\Ppob\ProjectRepository;
// use App\Models\Ppob\ProjectVersion;

class ProductResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $meta = [];
        if(is_array($this->meta)){
            $meta = array_map(function($v){
                if($v === 'true' || $v === 'false'){
                    return (boolean) $v;
                }else {
                    return (string) $v;
                }
            }, $this->meta);
        }

        $price = $this->price;

        
        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'slug' => (string) $this->slug,
            'code' => (string) $this->code,
            'image' => (string) $this->image,
            'description' => (string) $this->name,
            'danom' => (string) $this->denom,
            'provider' => (string) $this->provider,
            'order' => (int) $this->order,
            'currency' => 'IDR',
            'price' => (double) $this->price_sell,
            'admin_fee' => (double) $this->admin_fee,
            'profit_fee' => (double) ($this->price_sell - ($this->price_buy+$this->admin_fee)),
            'total' => (double) (($this->price_sell + $this->admin_fee)- $this->discount),
            //'meta' => (object) $meta
            'meta' => json_decode($this->meta, true)
        ];
    }

    
}
