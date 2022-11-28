<?php

namespace App\Resources\Ppob\Product;

use App\Resources\Ppob\Product\ProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


class ProductCollection extends ResourceCollection
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
            'data' => ProductResource::collection($this->collection),
            'meta' => [
                'execution_time' => 0
            ]
        ];
    }
}
