<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalProducts extends Model
{
    use HasFactory;

    protected $table = 'ppob.digital_products';
    protected  $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'properties' => 'array',
        'meta' => 'array',
        'admin_fee' => 'double',
        'base_price' => 'double',
    ];

    protected $fillable = ['name', 'code', 'slug', 'image', 'description', 'danom', 'provider', 'order', 'currency', 'price', 'price_agent', 'reseller_price', 'profit_fee', 'admin_fee', 'base_price', 'ppn',  'pph', 'status', 'category_id', 'parent_id', 'meta', 'icon', 'profit_percentage'];
    
    public function service()
    {
        return $this->hasMany(DigitalProductService::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
