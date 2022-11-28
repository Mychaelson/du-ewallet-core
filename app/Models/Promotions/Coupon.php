<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //

	protected $fillable = [
		"promo_id",
		"expired_at",
		"redeemed_at",
		"type",
		"code",
		"transaction_id",
		"ncash_trans_id",
		"value",
		"merchant",
		"product",
		"barcode",
		"released_by",
		"exchanged",
		"extra_payment",
		"user_id",
		"catalogue_id",
		"released_at",


	];

	public function promotion()
	{
		return null;//$this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'promotion_id');
	}

	public function merchantsimple()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant')->select('id', 'name', 'logo', 'description', 'owner_contact');
    }
    public function cataloguesimple()
    {
        return $this->hasOne(\App\Models\Promotions\Catalogue::class, 'id', 'catalogue_id')->select('id', 'name', 'exchange', 'description', 'terms', 'images');
    }
}
