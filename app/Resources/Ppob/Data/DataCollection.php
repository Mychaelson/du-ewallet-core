<?php

namespace App\Resources\Ppob\Data;

use App\Resources\Ppob\Data\DataResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


class DataCollection extends ResourceCollection
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
            'data' => DataResource::collection($this->collection),
            'meta' => [
                'execution_time' => 0
            ]
        ];
    }
}
