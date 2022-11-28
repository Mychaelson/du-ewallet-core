<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class StampRedemption extends Model
{
    //

	protected $table = 'promotions.stamp_redemption';
	protected $fillable = [
		"catalogue_id",
		"user_id",
		"released_by",
		"barcode",
		"transaction_id",
		"redeemed_at",
		"released_at",
		"expired_at",
	];

	protected $cast = [
		'redeemed_at' => 'string',
		'expired_at' => 'string',
	];

	public function promotion()
	{
		return null;//$this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'promotion_id');
	}

	// public function merchantsimple()
 //    {
 //        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant')->select('id', 'name', 'logo', 'description', 'owner_contact');
 //    }
    public function cataloguesimple()
    {
        return $this->hasOne(\App\Models\Promotions\StampCatalogue::class, 'id', 'catalogue_id')->select('id', 'name', 'merchant', 'stamp_required', 'description', 'images');
    }
}
