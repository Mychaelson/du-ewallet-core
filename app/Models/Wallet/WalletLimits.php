<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLimits extends Model
{
    use HasFactory;
    protected $table = 'wallet.wallet_limits';
}
