<?php

namespace App\Resources\Wallet\WalletsLabel;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon;
use App\Repositories\Wallet\WalletsRepository;
use App\Models\Wallet\WalletLabels;

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
        $data = [
            'id'            =>  $this->id,
            'user'          =>  $this->user,
            'name'          =>  $this->name,
            'icon'          =>  $this->icon,
            'background'    =>  $this->background,
            'color'         =>  $this->color,
            'spending'      =>  $this->spending,
            'default'       =>  $this->default,
            'organization'  =>  $this->organization,
            'updated'       =>  $this->updated_at,
            'created'       =>  $this->created_at
        ];

        return $data;
    }

}
