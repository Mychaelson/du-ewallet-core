<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class MerchantMeta extends Model
{
    //
	protected $table = "promotions.metas";

	protected $fillable = [
	];

	public function addresses()
	{
		return $this->hasOne(\App\Models\Promotions\Merchant::class, 'merchant_id');
	}
}
