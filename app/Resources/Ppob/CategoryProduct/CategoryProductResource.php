<?php

namespace App\Resources\Ppob\CategoryProduct;

use App\Resources\Ppob\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource as BaseResource;

class CategoryProductResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'slug' => (string) $this->slug,
            'description' => (string) $this->description,
            'image' => (string) $this->image,
            'is_parent' => (boolean) $this->is_parent === 0,
            'group' => (string) $this->group,
            'products' => ProductResource::collection($this->whenLoaded('products'))
        ];
    }

    
}
