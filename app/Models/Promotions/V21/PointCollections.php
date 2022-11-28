<?php

namespace App\Models\Promotions\V21;

use Illuminate\Database\Eloquent\Model;

class PointCollections extends Model
{
    //

    protected $table = "promotions.point_collections";
    protected $fillable = [
        'promotion_user_id',
        'point_id',
        'point',
        'spent',
        'point_transactionid',
        'expired_at',
    ];


    protected $dates = [
        'expired_at',
    ];

    protected $casts = [
    ];

	public function point()
	{
		return $this->belongsTo(\App\Models\Promotions\V21\Point::class);
	}

    public function pointuser()
    {
        return $this->hasOne(\App\Models\Promotions\PromotionUser::class, 'id', 'promotion_user_id');
    }
    
}
