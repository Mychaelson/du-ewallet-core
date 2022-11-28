<?php

namespace Database\Seeders\Cart;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Card extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment.card')->insert([
            'user' => 26,
            'bank' => '{"id":"VISA","label":"Visa","logo":{"card":"https:\/\/cdn.nusapay.co.id\/icon\/card\/ecard-visa.png","logo":"https:\/\/cdn.nusapay.co.id\/icon\/card\/logo-visa.png"}}',
            'number' => '4259450300667554',
            'exp_year' => '2022',
            'exp_month' => '07',
            'status' => 2,
            'visibility' => 0,
        ]);
    }
}
