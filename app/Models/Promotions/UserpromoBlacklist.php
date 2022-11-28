<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class UserpromoBlacklist extends Model
{
    //
	protected $table = "promotions.userpromo_blacklist";

	protected $fillable = [
		"type",
		"promo",
		"user",
		"description"
	];
}
