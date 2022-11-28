<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionMerchantFilter extends Model
{
    //

	protected $table = "promotions.merchant_filter";

    protected $fillable = [
    	"promotion_id",
    	"merchant_id"
    ];

	public function product()
	{
		return $this->hasOne(\App\Models\Promotions\Merchant::class,'id', 'merchant_id');
	}
}
