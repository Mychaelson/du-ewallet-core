<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppob.settings')->insert([
                    [
                        'id' => 1,
                        'key' => 'operation_cost',
                        'currency' => 'IDR',
                        'value' => '250',
                        'meta' => ''
                    ],
                    [
                        'id' => 2,
                        'key' => 'daily_product_limit',
                        'currency' => 'IDR',
                        'value' => '[\"TSWF07\",\"TSWF30\",\"TSWF01\"]',
                        'meta' => ''
                    ]
                
        ]);
    }
}
