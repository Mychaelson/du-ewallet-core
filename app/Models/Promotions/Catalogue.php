<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    //

	protected $table = "promotions.coupon_catalogue";

	protected $fillable = [
		"name",
		"category",
		"slug",
		"exchange",
		"exchange_extra",
		"description",
		"terms",
		"images",
		"value",
		"merchant",
		"product",
		"quantity",
		"quantity_exchanged",
		"created_by",
		"start_at",
		"end_at",
		"type",
	];

	protected $casts = [
		"name" => 'string',
		"category" => 'integer',
		"slug" => 'string',
		"exchange" => 'integer',
		"exchange_extra" => 'double',
		"description" => 'string',
		"terms" => 'string',
		"images" => 'json',
		"value" => 'integer',
		"merchant" => 'integer',
		"product" => 'integer',
		"quantity" => 'integer',
		"quantity_exchanged" => 'integer',
		"created_by" => 'integer',
		"start_at" => 'date',
		"end_at" => 'date',
		"type" => 'integer',
	];

	public function promotion()
	{
		return null;//$this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'promotion_id');
	}
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

    public function appliedMerchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'applied_merchant');
    }

    public function merchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant');
    }
}
