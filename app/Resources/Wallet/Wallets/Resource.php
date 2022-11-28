<?php

namespace App\Resources\Wallet\Wallets;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Wallet\WalletsRepository;
use App\Models\Wallet\Wallets;

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
            'locker'        =>  $this->locker,
            'user_id'       =>  (int)$this->user_id,
            'currency'      =>  $this->currency,
            'balance'       =>  (int)$this->balance,
            'ncash'         =>  (int)$this->ncash,
            'hold'          =>  (int)$this->hold,
            'reversal'      =>  (int)$this->reversal,
            'type'          =>  (int)$this->type,
            'merchant'      =>  $this->merchant,
            'lock_in'       =>  ($this->lock_in == 0) ? false : true,
            'lock_out'      =>  ($this->lock_out == 0) ? false : true,
            'lock_wd'       =>  ($this->lock_wd == 0) ? false : true,
            'lock_tf'       =>  ($this->lock_tf == 0) ? false : true,
            'lock_nv_rdm'   =>  ($this->lock_nv_rdm == 0) ? false : true,
            'lock_pm'       =>  ($this->lock_pm == 0) ? false : true,
            'lock_nv_crt'   =>  ($this->lock_nv_crt == 0) ? false : true,
            'updated'       =>  $this->updated_at,
            'created'       =>  $this->created_at
        ];

        return $data;
    }
    
}
