<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppob.service_v2')->insert([
            [
                'name' => 'Portal Pulsa',
                'status' => 1,
                'description' => 'PPOB Portal Pulsa',
                'contact' => '+6285727700650',
                'balance' => 0,
                'attachment' => null,
                'path' => 'App\Repositories\Ppob\Vendor\Service\PostalPulsa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Raja Biller',
                'status' => 1,
                'description' => 'PPOB Raja Biller',
                'contact' => '+6285727700650',
                'balance' => 0,
                'attachment' => null,
                'path' => 'App\Repositories\Ppob\Vendor\Service\RajaBiller',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
