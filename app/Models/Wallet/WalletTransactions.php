<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransactions extends Model
{
    use HasFactory;
    protected $table = 'wallet.wallet_transactions';
    protected $fillable = ['wallet_id','reff_id','amount','transaction_type','status','note','location'];
}
