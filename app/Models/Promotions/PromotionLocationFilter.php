<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionLocationFilter extends Model
{
    //

	protected $table = "promotions.location_filter";

    protected $fillable = [
    	"promotion_id",
    	"location_id"
    ];

	public function product()
	{
		return $this->hasOne(\App\Models\Promotions\Merchant::class,'id', 'merchant_id');
	}
}
