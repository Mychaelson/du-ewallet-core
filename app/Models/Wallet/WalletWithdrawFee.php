<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletWithdrawFee extends Model
{
    use HasFactory;

    protected $table = 'wallet.wallet_withdraw_fee';

    protected $casts = [
        'fee' => 'decimal',
    ];
}
