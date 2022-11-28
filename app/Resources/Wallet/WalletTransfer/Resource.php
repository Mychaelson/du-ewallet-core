<?php

namespace App\Resources\Wallet\WalletTransfer;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Wallet\WalletsRepository;
use App\Models\Wallet\WalletTransfers;

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
            'id'                =>  $this->id,
            'message'           =>  $this->message,
            'message_reply'     =>  $this->message_reply,
            'background'        =>  $this->background,
            'from'              =>  $this->from,
            'to'                =>  $this->to,
            'amount'            =>  $this->amount,
            'reff'              =>  $this->reff,
            'description_from'  =>  $this->description_from,
            'description_to'    =>  $this->description_to,
            'schedule'          =>  $this->schedule,
            'repeat'            =>  $this->repeat,
            'updated'           =>  $this->updated_at,
            'created'           =>  $this->created_at
        ];

        return $data;
    }
    
}
