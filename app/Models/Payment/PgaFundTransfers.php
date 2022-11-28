<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PgaFundTransfers extends Model
{
    use HasFactory;
    protected $table = 'payment.pga_fund_transfers';
    protected $fillable = [
        'transaction_id',
        'action',
        'reference_no',
        'unique_id',
        'amount',
        'fee',
        'merchant_surcharge_rate',
        'charge_to',
        'payout_amount',
        'disbursement_status',
        'disbursement_description',
        'bank_code',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'error_message'
    ];
}
