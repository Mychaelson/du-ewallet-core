<?php

namespace Database\Seeders\ATM;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ATMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.atm')->insert([
            'id' => 2,
            'title' => 'Head Office',
            'address' => 'Foresta Business Loft 5
            Lengkong Kulon, Pagedangan,
            Tangerang, Banten 15331',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }
}
