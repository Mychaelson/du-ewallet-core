<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    use HasFactory;

    protected $table = 'payment.bill_payment';
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
