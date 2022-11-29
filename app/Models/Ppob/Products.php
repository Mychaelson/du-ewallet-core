<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = ['code',
        'name',
        'slug',
        'description',
        'provider',
        'category_id',
        'denom',
        'price_sell',
        'price_buy',
        'admin_fee',
        'status',
        'service_id',
        'meta'
    ];
    protected $table = 'ppob.products';

    public function service()
    {
        return $this->hasMany(ProductService::class, 'product_code');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
