<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PointCollection extends Model
{
    //
	protected $table = "promotions.point_collection";

	protected $fillable = [
		"merchant_id",
		"promotion_id",
		"promotion_user_id",
		"details",
		"point_transactionid",
		"status",
		"available_point",
		"spent_point",
		"collected_point",
		"status"
	];
}
