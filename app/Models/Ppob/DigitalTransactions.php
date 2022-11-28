<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalTransactions extends Model
{
    use HasFactory;

    protected $table = 'ppob.digital_transactions';
    protected  $primaryKey = 'id';
    public $timestamps = false;
    protected $casts = [
        'product_snap' => 'array',
        'request_data' => 'array',
        'inquiry_data' => 'array',
        'result' => 'array',
        'response_data' => 'array',
        'meta' => 'array',
        'payment_information' => 'array'
    ];

    protected $fillable = ['uuid', 'code', 'order_id', 'currency', 'user_id', 'phone', 'customer_id', 'price', 'admin_fee', 'amount', 'discount_amount', 'voucher_amount', 'ncash', 'total', 'base_price', 'status', 'biller_id', 'service', 'product_snap', 'request_data', 'inquiry_data', 'result', 'response_data', 'meta', 'type', 'category', 'payment_channel', 'payment_information'];

}
