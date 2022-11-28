<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionMeta extends Model
{
    protected $table = "promotions.promotion_meta";

    protected $fillable = [
    	"promotion_id",
    	"key",
    	"value"
    ];


    public function promotion()
    {
        return $this->belongsTo(\App\Models\Promotions\Promotion::class, 'promotion_id', 'id');
    }

}
