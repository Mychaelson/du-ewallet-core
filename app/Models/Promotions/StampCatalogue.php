<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class StampCatalogue extends Model
{
    //

	protected $table = "promotions.stamp_catalogue";

	protected $fillable = [
		"name",
		"slug",
		"merchant",
		"category",
		"description",
		"images",
		"quantity",
		"quantity_exchanged",
		"start_at",
		"end_at",
		"terminated_at",
		"status",
		"stamp_required",
		"properties",
		"claim_p_day",
		"claim_p_day_user",
		"created_by",
		"user_id",
	];

	protected $casts = [
		"name" => 'string',
		"slug" => 'string',
		"merchant" => 'integer',
		"stamp_required" => 'integer',
		"category" => 'string',
		"description" => 'string',
		"images" => 'json',
		"quantity" => 'integer',
		"quantity_exchanged" => 'integer',
		"start_at" => 'date',
		"end_at" => 'date',
		"terminated_at" => 'string',
		"status" => 'string',
		"properties" => 'json',
		"claim_p_day" => 'integer',
		"claim_p_day_user" => 'integer',
		"created_by" => 'integer',
		"user_id" => 'integer',
	];

	public function merchantsimple()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant')->select('id', 'name', 'logo', 'description');
    }

    public function catalogue()
    {
        return $this->belongsTo(\App\Models\Promotions\ProductCategories::class, 'category', 'id');
    }
    public function cataloguesimple()
    {
        return $this->belongsTo(\App\Models\Promotions\ProductCategories::class, 'category', 'id')->select('id','name');
    }

    public function merchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant');
    }
}
