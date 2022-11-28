<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletWithdraw extends Model
{
    use HasFactory;

    protected $table = 'wallet.wallet_withdraw';

    protected $casts = [
        'notify' => 'array',
        'amount' => 'double',
        'total' => 'double',
        'pg_fee' => 'double',
    ];
}
