<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'payment.bill';
    protected  $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'amount' => 'double',
    ];

}
