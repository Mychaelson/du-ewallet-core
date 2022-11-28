<?php

namespace App\Resources\Lang\PublicApi\Project;

use App\Resources\Lang\PublicApi\Project\Resource;
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
            'description' => "OK",
            'response_code' => 200,
            'data' => Resource::collection($this->collection),
        ];
    }
}
