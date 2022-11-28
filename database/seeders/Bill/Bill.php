<?php

namespace Database\Seeders\Bill;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Bill extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment.bill')->insert([
            'user' => 26,
            'merchant' => 1,
            'status' => 1,
            'description' => 'Test Pembayaran Dengan JENIUS',
            'invoice' => 'PMT-0003',
            'callback' => 'http://localhost/',
            'currency' => 'IDR',
            'amount' => 10000.0,
            'paid' => 0,
            'cashback' => 0,
            'wallet' => 'local',
            'reason' => 'Failed on 2C2P',
            'pushed' => null,
            'refund' => null,
            'expires' => '2019-12-19 09:33:50',
        ]);
    }
}
