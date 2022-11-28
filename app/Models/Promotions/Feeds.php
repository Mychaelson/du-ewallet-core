<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    //
    protected $table = "promotions.feeds";

	protected $fillable = [
		"created_by",
		"title",
		"description",
		"image",
		"action",
		"action_destiny",
		"merchant_id",
		"date_to"
	];

	protected $casts = [
		'date_to' => 'datetime'
	];

	public function merchant()
	{
		return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant_id');
	}
}
