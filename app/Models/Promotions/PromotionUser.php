<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionUser extends Model
{
    //
	protected $table = "promotions.promotion_user";

	protected $fillable = [
		"merchant_id",
		"user_id",
		"available_point",
		"spent_point",
		"collected_point",
		"expired_point",
		"status"
	];
}
