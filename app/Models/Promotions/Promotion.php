<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    //

    protected $fillable = [
    	"promotion_name",
    	"promotion_slug",
    	"minimum_transaction_amount",
    	"date_from",
    	"date_to",
    	"budget",
    	"budget_available",
        "budget_used",
    	"promotion_value",
    	"max_claim_amount_per_order",
    	"max_claim_amount_per_user",
    	"max_claim_per_user",
    	"max_claim_per_day",
    	"claim_from",
    	"claim_to",
    	"merchant_id",
    	"created_by",
    	"wallet_id",
    	"nda_id",
    	"transaction_id",
    	"promotion_type",
    	"promotion_count",
    	"is_fixed_code",
    	"promotion_prefix",
    	"promotion_code_length",
    	"is_percentage",
    	"promotion_amount",
    	"promotion_amount_available",
    	"status",
        "properties",
        'terms'
    ];

    // status
    // 0 = new
    // 1 = active
    // 2 = need revision
    // 3 = in review
    // 4 = new revision
    // 5 = termination process
    // 6 = terminated

    protected $dates = [
        'termination_request',
        'terminated_date_to',
        'created_at',
        'updated_at',
        'date_from',
        'date_to'
    ];

    protected $casts = [
        'properties' => 'json'
    ];

	public function itemsum()
	{
		return $this->hasMany(\App\Models\Promotions\Cashback::class,'promotion_id', 'id');
	}


    public function productfilter()
    {
        return $this->hasMany(\App\Models\Promotions\PromotionProductFilter::class, 'promotion_id', 'id');
    }
    public function promotionfilter()
    {
        return $this->hasOne(\App\Models\Promotions\PromotionFilter::class, 'promotion_id', 'id');
    }

    public function stampcollection()
    {
        return $this->hasOne(\App\Models\Promotions\StampCollection::class, 'promotion_id', 'id');
    }

    public function stampfilter()
    {
        return $this->hasOne(\App\Models\Promotions\PromotionFilter::class, 'promotion_id', 'id');
    }
    public function stampproduct()
    {
        return $this->hasOne(\App\Models\Promotions\PromotionProduct::class, 'promotion_id', 'id');
    }

    public function stampproductredeemed()
    {
        return $this->hasone(\App\Models\Promotions\ProductRedeem::class, 'promotion_id', 'id');
    }

    public function merchantfilter()
    {
        return $this->hasMany(\App\Models\Promotions\PromotionProductFilter::class, 'promotion_id', 'id');
    }

    public function merchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant_id');
    }

    public function locationfilter()
    {
        return $this->hasMany(\App\Models\Promotions\PromotionLocationFilter::class, 'promotion_id', 'id');
    }

    public function revision()
    {
        return $this->hasMany(\App\Models\Promotions\PromotionRevision::class, 'promotion_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Promotions\TransactionLog::class, 'promotion_id', 'id');
    }

    public function jarak($query, $lat, $lng, $distance = 0)
    {
        return $query->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance', [
                $lat, 
                $lng, 
                $lat
            ])
            ->leftJoin('promotion_meta as lat', function($join){
                $join->on('lat.promotion_id', 'promotions.id');
                $join->on('lat.key', 'lat');
            })
            ->leftJoin('promotion_meta as lng', function($join){
                $join->on('lng.promotion_id', 'promotions.id');
                $join->on('lng.key', 'lng');
            });
        // ->havingRaw('distance < ?', [ $distance ]);
    }
    
}
