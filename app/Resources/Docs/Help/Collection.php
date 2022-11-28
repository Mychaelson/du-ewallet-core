<?php

namespace App\Resources\Docs\Help;

use App\Resources\Docs\Help\Resource;
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
            'success' => TRUE,
            'response_code' => 200,
            'data' => Resource::collection($this->collection) ? Resource::collection($this->collection) : [],
        ];
    }
}
