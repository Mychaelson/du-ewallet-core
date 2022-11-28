<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class MerchantAddresses extends Model
{
    //
	protected $table = "promotions.merchant_branch";

	protected $fillable = [
	];

	public function merchant()
	{
		return $this->belongsTo(\App\Models\Promotions\Merchant::class);
	}

	public function village()
    {
        return $this->belongsTo(\App\Models\Promotions\Villages::class);
    }
}
