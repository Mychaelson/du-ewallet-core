<?php

namespace App\Resources\Promotions\PromoRequest;

use Illuminate\Http\Resources\Json\Resource as BaseResource;

class Resource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $properties = [];
        
        return [
            'id' => (int) $this->id,
            'start' => (string) $this->start,
            'end' => (string) $this->end,
            'name' => (string) $this->name,
            'description' => (string) $this->description,
            'status' => (string) $this->status
        ];
    }
}
