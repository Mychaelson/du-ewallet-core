<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransfers extends Model
{
    use HasFactory;
    protected $table = 'wallet.wallet_transfers';
    protected $fillable = ['from','to','label','amount','message','background','reff','description_from','description_to','schedule','repeat'];
}
