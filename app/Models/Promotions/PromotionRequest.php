<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionRequest extends Model
{
    //

	protected $table = 'promotions.promotion_request';
    protected $fillable = [
    	"start",
    	"end",
    	"description",
    	"user",
    	"merchant",
    	"status",
        'name',
    ];

    
}
