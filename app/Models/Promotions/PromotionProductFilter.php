<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionProductFilter extends Model
{
    //

	protected $table = "promotions.product_filter";

    protected $fillable = [
    	"promotion_id",
    	"product_id"
    ];

	public function product()
	{
		return $this->hasOne(\App\Models\Promotions\Product::class,'id', 'product_id');
	}
}
