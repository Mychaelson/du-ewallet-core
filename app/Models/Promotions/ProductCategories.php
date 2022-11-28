<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    //

	protected $table = "promotions.product_categories";

    protected $fillable = [
    	
    ];

    public $timestamps = false;
	// public function itemsum()
	// {
	// 	return $this->hasMany(\App\Models\Promotions\Cashback::class,'promotion_id', 'id');
	// }
}
