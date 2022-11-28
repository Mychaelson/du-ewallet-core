<?php

namespace App\Resources\Promotions\Agreement;

use App\Resources\Promotions\Agreement\ProgressResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProgressCollection extends ResourceCollection
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
            'data' => ProgressResource::collection($this->collection),
        ];
    }
}
