<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionV2 extends Model
{
    use HasFactory;
    protected $table = 'ppob.transaction_v2';
    protected $fillable = [
        'user_id', 
        'product_code', 
        'label_id', 
        'invoice_no', 
        'product_type',
        'price_sell', 
        'admin_fee', 
        'discount', 
        'total', 
        'price_service',
        'admin_fee_service',
        'profit',
        'status',
        'service_id',
        'req_inquiry',
        'res_inquiry',
        'req_payment',
        'res_payment'
    ];
}
