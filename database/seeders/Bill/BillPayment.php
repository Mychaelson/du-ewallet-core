<?php

namespace Database\Seeders\Bill;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillPayment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment.bill_payment')->insert([
            'bill' => 1,
            'status' => 1,
            'method' => 'card',
            'internal' => 0,
            'data' => '{"create":{"id":"1","user":"6617","bank":"{\"id\":\"VISA\",\"label\":\"Visa\",\"logo\":{\"card\":\"https:\\\/\\\/cdn.nusapay.co.id\\\/icon\\\/card\\\/ecard-visa.png\",\"logo\":\"https:\\\/\\\/cdn.nusapay.co.id\\\/icon\\\/card\\\/logo-visa.png\"}}","number":"4661601003992572","exp_year":"2022","exp_month":"10","status":"1","visibility":"1","updated":"2019-12-19 06:34:36","created":"2019-12-19 06:34:36"}}',
            'amount' => 10000.0,
            'object' => 1,
        ]);
    }
}
