<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    //
	protected $table = "promotions.merchants";

	protected $fillable = [
	];

	public function addresses()
	{
		return $this->hasMany(\App\Models\Promotions\MerchantAddresses::class);
	}
}
