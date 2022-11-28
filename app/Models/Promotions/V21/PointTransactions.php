<?php

namespace App\Models\Promotions\V21;

use Illuminate\Database\Eloquent\Model;

class PointTransactions extends Model
{
    //

    protected $table = "promotions.point_transactions";
    protected $fillable = [
        'promotion_user_id',
        'point_id',
        'point',
        'type',
        'note',
        'product_id',
        'identifier',
        'committed_at'
    ];


    protected $dates = [
        'redeemed_at',
        'expired_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'redeemed_at' => 'string',
        'expired_at' => 'string',
    ];

	public function pointRelation()
    {
        return $this->hasOne(\App\Models\Promotions\V21\Point::class, 'id', 'point_id');
    }

    public function product()
    {
        return $this->hasOne(\App\Models\Promotions\PromotionProduct::class,'id', 'product_id');
    }
    
}
