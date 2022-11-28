<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppob.billers')->insert([
            [
                'id' => 2,
                'name' => 'Portal Pulsa',
                'description' => 'PPOB Portal Pulsa',
                'contact' => '+6285727700650',
                'balance' => 0,
                'attachment' => null,
                'created_at' => '2018-05-08 00:57:11',
                'updated_at' => '2021-07-05 19:20:02'
            ],
        ]);
    }
}
