<?php

namespace Database\Seeders\Wallet;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallet.wallets')->insert([
            'id' => '1',
            'locker' => '1-IDR',
            'user_id' => '1',
            'currency' => 'IDR',
            'balance' => '0',
            'ncash' => '0',
            'hold' => '0',
            'reversal' => '0',
            'type' => '1',
            'merchant' => '0',
            'lock_in' => '0',
            'lock_out' => '0',
            'lock_wd' => '0',
            'lock_tf' => '0',
            'lock_nv_rdm' => '0',
            'lock_pm' => '0',
            'lock_nv_crt' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('wallet.wallets')->insert([
            'id' => '2',
            'locker' => '1-IDR-2',
            'user_id' => '1',
            'currency' => 'IDR',
            'balance' => '0',
            'ncash' => '0',
            'hold' => '0',
            'reversal' => '0',
            'type' => '2',
            'merchant' => '0',
            'lock_in' => '0',
            'lock_out' => '0',
            'lock_wd' => '0',
            'lock_tf' => '0',
            'lock_nv_rdm' => '0',
            'lock_pm' => '0',
            'lock_nv_crt' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
