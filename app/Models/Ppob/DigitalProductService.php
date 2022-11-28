<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalProductService extends Model
{
    use HasFactory;
    protected $table = 'ppob.digital_product_services';
    protected $casts = [
        'meta' => 'array'
    ];

    protected $fillable = ['product_id', 'service_id', 'base_price', 'admin_fee', 'code', 'meta', 'status'];

    public function product()
    {
        return $this->belongsTo(DigitalProducts::class, 'product_id');
    }

    public function switcher()
    {
        return $this->belongsTo(BillerService::class, 'service_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

}
