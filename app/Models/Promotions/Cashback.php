<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    //
    protected $table = 'promotions.cashback';

	protected $fillable = [
		"promotion_id",
		"percentage",
		"amount",
		"transaction_amount",
		"transaction_ref",
		"coupon",
		"transaction_id",
		"redeemed_by",
		"redeemed_at",
		'referral',
		"status",
		"note",
		"expired_at",
	];

	public function promotion()
	{
		return $this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'promotion_id');
	}

	public function promotionsimple()
	{
		return $this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'promotion_id')->select('id','name');
	}

	public function couponsimple()
	{
		return $this->hasOne(\App\Models\Promotions\Coupon::class,'id', 'coupon')->select('id','ncash_trans_id', 'catalogue_id');
	}
}
