<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionRedeem extends Model
{
    //
	protected $table = "promotions.promotion_redeems";

	protected $fillable = [
		"promotion_id",
		"promotion_type",
		"amount",
		"transaction_amount",
		"transaction_ref",
		"transaction_id",
		"redeemed_by",
		"redeemed_at",
		"metas",
		"status"
	];
}
