<?php

namespace App\Resources\Promotions\UserStamp;

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
        
        return [
            'id' =>  $this->id,
            'collection_id' => (int) $this->collection_id,
            'stamp_required' => (int) $this->stamp_required,
            'collected' => (int) $this->collected,
            'available' => (int) $this->available,
            'spent' => (int) $this->spent,
            'expired_at' => null!=$this->expired_at?(string)$this->expired_at:null,
            'name' => $this->name,
            'image' => $this->images[0] ?? null,
            'images' => $this->images,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
