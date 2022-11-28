<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PgaBills extends Model
{
    use HasFactory;
    protected $table = 'payment.pga_bills';
    protected $fillable = [
    'transaction_id',
    'action',
    'merchant_code',
    'reference_no',
    'invoice_no',
    'customer_name',
    'customer_phone',
    'customer_email',
    'description',
    'net_amount',
    'surcharge',
    'surcharge_to',
    'amount',
    'payment_method',
    'payment_method_code',
    'pay_code',
    'pay_qrcode',
    'pay_checkout_url',
    'pay_mobile_deeplink',
    'paid_status',
    'paid_description',
    'form_url',
    'status',
    'error_message'
    ];
}
