<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionFilter extends Model
{
    protected $table = "promotions.promotion_filter";

    protected $fillable = [
    	"promotion_id",
    	"merchant_id",
    	"filter_type",
    	"created_by",
    	"filter_name",
    	"filter_slug",
    	"filter_operator",
        "filter_compare",
    	"filter_compareto",
    	"filter_amount",
    	"start",
    	"end",
        "time_start",
        "time_end",
    	"deleted_at",
    	"status"
    ];


    public function promotion()
    {
        return $this->belongsTo(\App\Models\Promotions\Promotion::class, 'promotion_id', 'id');
    }

    public function merchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant_id');
    }

}
