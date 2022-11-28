<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class CouponTransaction extends Model
{
    //

	protected $table = 'promotions.coupon_transaction';

	protected $fillable = [
		"user_id",
		"coupon_id",
		"type",
		"info",
		"identitier",
		"meta",
	];

	protected $casts = [
		"user_id" => 'integer',
		"coupon_id" => 'integer',
		"type" => 'integer',
		"info" => 'string',
		// "identitier" => 'string',
		"meta" => 'json',
	];

	public function promotion()
	{
		return null;//$this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'promotion_id');
	}
}
