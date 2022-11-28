<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchingFeeBanks extends Model
{
    use HasFactory;
    protected $table = 'wallet.switching_fee_banks';
}
