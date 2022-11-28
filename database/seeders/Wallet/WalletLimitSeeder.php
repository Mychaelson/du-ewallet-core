<?php

namespace Database\Seeders\Wallet;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DB::table('wallet.wallet_limits')->insert([
            'id' => '1',
            'wallet' => '1',
            'withdraw_daily' => '2000000',
            'transfer_daily' => '2000000',
            'payment_daily' => '2000000',
            'topup_daily' => '2000000',
            'switching_max' => '2000000',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]); */
    }
}
