<?php

namespace App\Resources\Promotions\Agreement;

use App\Resources\Promotions\Agreement\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
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
            'data' => Resource::collection($this->collection),
        ];
    }
}
