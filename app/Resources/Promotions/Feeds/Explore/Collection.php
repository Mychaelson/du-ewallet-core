<?php

namespace App\Resources\Promotions\Feeds\Explore;


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
Resource::collection($this->collection);
    }
}
