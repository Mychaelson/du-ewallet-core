<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ppob\DigitalTransactions;

class PaymentSchedules extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'customer_id', 'product_id', 'code', 'category', 'payment_at', 'repeat', 'last_payment', 'price', 'on_schedule', 'last_inquiry', 'transaction_id', 'status', 'wallet_hash', 'wallet_id', 'note'];
    protected $table = 'ppob.payment_schedules';
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
