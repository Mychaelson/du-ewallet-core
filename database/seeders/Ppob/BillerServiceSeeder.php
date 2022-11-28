<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppob.biller_services')->insert([
            [
                'id' => 10,
                'name' => 'Portal Pulsa',
                'desctription' => null,
                'biller_id' => 2,
                'service' => 'App\\Repositories\\Ppob\\Vendor\\Service\\PortalPulsa',
                'status' => 1,
                'created_at' => '2018-05-08 07:30:08',
                'updated_at' => '2018-12-13 05:22:34'
            ],
            // [
            //     'id' => 5,
            //     'name' => 'Portal Pulsa Gopay',
            //     'desctription' => null,
            //     'biller_id' => 2,
            //     'service' => 'App\\Biller\\Gopay\\Portalpulsa\\Topup',
            //     'status' => 1,
            //     'created_at' => '2018-05-08 07:30:08',
            //     'updated_at' => '2018-12-13 05:22:34'
            // ],
            // [
            //     'id' => 10,
            //     'name' => 'Portal Pulsa Pulsa',
            //     'desctription' => null,
            //     'biller_id' => 2,
            //     'service' => 'App\\Biller\\Pulsa\\Portalpulsa\\Pulsa',
            //     'status' => 1,
            //     'created_at' => '2018-05-09 02:51:27',
            //     'updated_at' => '2019-02-06 23:01:12'
            // ],
            // [
            //     'id' => 11,
            //     'name' => 'Portal Pulsa Games',
            //     'desctription' => null,
            //     'biller_id' => 2,
            //     'service' => 'App\\Biller\\Games\\Portalpulsa\\Voucher',
            //     'status' => 1,
            //     'created_at' => '2018-05-09 02:51:35',
            //     'updated_at' => '2018-05-09 02:51:35'
            // ],
            // [
            //     'id' => 100,
            //     'name' => 'Portal pulsa Balance',
            //     'desctription' => null,
            //     'biller_id' => 2,
            //     'service' => 'App\\Biller\\Provider\\PortalpulsaBalance',
            //     'status' => 1,
            //     'created_at' => '2018-05-09 02:51:47',
            //     'updated_at' => '2018-05-09 02:51:47'
            // ]
                
        ]);
    }
}
