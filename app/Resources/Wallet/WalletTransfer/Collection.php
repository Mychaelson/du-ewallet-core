<?php

namespace App\Resources\Wallet\WalletTransfer;

use App\Resources\Wallet\WalletTransfer\Resource;
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
            'data' => Resource::collection($this->collection),
            'total' =>(int) Resource::collection($this->collection)->count()
        ];
    }
}
