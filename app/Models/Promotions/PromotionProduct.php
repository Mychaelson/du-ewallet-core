<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionProduct extends Model
{
    //

    protected $table = 'promotions.promotion_product';

    protected $fillable = [
    	"promotion_id",
    	"merchant_id",
    	"category_id",
    	"promotion_type",
    	"product_name",
    	"product_slug",
    	"exchange_amount",
        "exchange_extra_amount",
    	"description",
    	"images",
    	"product_value",
        "voucher_code",
        "applied_merchant",
        "digital_product_id",
    	"exchange_quantity",
    	"exchanged_count",
    	"created_by",
    	"status",
    	"created_at",
        "expired_at",
    	"updated_at"
    ];

    protected $casts =[
        'images' => 'json',
        'expired_at' => 'datetime',
        "promotion_id" => 'integer',
        "merchant_id" => 'integer',
        "category_id" => 'integer',
        "promotion_type" => 'integer',
        "product_name" => 'string',
        "product_slug" => 'string',
        "exchange_amount" => 'double',
        "exchange_extra_amount" => 'double',
        "description" => 'string',
        "product_value" => 'double',
        "voucher_code" => 'string',
        "applied_merchant" => 'integer',
        "digital_product_id" => 'integer',
        "exchange_quantity" => 'integer',
        "exchanged_count" => 'integer',
        "created_by" => 'integer',
        "status" => 'integer',
    ];

	public function promotion()
	{
		return $this->belongsTo(\App\Models\Promotions\Promotion::class,'promotion_id', 'id');
	}

    public function catalogue()
    {
        return $this->belongsTo(\App\Models\Promotions\ProductCategories::class, 'category_id', 'id');
    }

    public function appliedMerchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'applied_merchant');
    }

    public function merchant()
    {
        return $this->hasOne(\App\Models\Promotions\Merchant::class, 'id', 'merchant_id');
    }

    
}
