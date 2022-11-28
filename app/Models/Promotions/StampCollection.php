<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class StampCollection extends Model
{
    //
	protected $table = "promotions.stamp_collections";

	protected $fillable = [
		"stampcat_id",
		"merchant",
		"promotion_user_id",
		"collected",
		"available",
		"spent",
		"expired_at",
	];

	protected $casts = [
		'stampcat_id' => 'integer',
		'merchant' => 'integer',
		'promotion_user_id' => 'integer',
		'collected' => 'integer',
		'available' => 'integer',
		'spent' => 'integer',
		'expired_at' => 'date',
		'images' => 'json',
	];

	public function promotion()
	{
		return $this->hasOne(\App\Models\Promotions\StampCatalogue::class,'id', 'promotion_id');
	}

	public function promotionsimple()
	{
		return $this->hasOne(\App\Models\Promotions\Promotion::class,'id', 'promotion_id')->select(['id', 'name', 'description', 'images']);
	}

}
