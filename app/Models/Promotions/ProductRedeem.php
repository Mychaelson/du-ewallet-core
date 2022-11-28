<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class ProductRedeem extends Model
{
    //
	protected $table = "promotions.product_exchange";

	protected $fillable = [
		"merchant_id",
		"product_id",
		"promotion_id",
		"promotion_type",
		"exchanged_amount",
		"extra_amount_paid",
		"exchange_code",
		"promotion_user_id",
		"is_voucher",
		"released_at",
		"expired_at",
		"released_by"
	];



	protected $casts = [
		'released_at' => 'datetime',
		'expired_at' => 'datetime',
		"merchant_id" => 'integer',
		"product_id" => 'integer',
		"promotion_id" => 'integer',
		"promotion_type" => 'integer',
		"exchanged_amount" => 'double',
		"extra_amount_paid" => 'double',
		"exchange_code" => 'string',
		"promotion_user_id" => 'integer',
		"is_voucher" => 'boolean',
		"released_by" => 'integer'
	];

	public function product()
	{
		return $this->hasOne(\App\Models\Promotions\PromotionProduct::class, 'id', 'product_id');
	}

	public function promotion()
	{
		return $this->hasOne(\App\Models\Promotions\Promotion::class, 'id', 'promotion_id');
	}

	public function merchant()
	{
		return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant_id');
	}

}
