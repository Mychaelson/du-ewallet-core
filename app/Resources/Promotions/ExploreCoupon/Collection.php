<?php

namespace App\Resources\Promotions\ExploreCoupon;

use App\Resources\Promotions\ExploreCoupon\Resource;
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
            'data' => [
                'menu_title' => 'Special Offer',
                'description' => 'Redeem coupons with your points',
                'items' => Resource::collection($this->collection)
            ],
        ];
    }
}
