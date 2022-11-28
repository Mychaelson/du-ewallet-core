<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_code',
        'service_id',
        'base_price',
        'admin_fee',
        'code',
        'status'];
    protected $table = 'ppob.product_services';

    public function product()
    {
        return $this->belongsTo(ProductV2::class, 'product_code');
    }
}
