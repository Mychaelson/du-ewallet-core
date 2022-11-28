<?php

namespace App\Resources\Ppob\CategoryProduct;

use App\Resources\Ppob\CategoryProduct\CategoryProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


class CategoryProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'success' => true,
            'response_code' => 200,
            'data' => CategoryProductResource::collection($this->collection),
            'meta' => [
                'execution_time' => 0
            ]
        ];
    }
}
