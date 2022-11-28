<?php

namespace Database\Seeders\Accounts;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AccountsCountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.countries')->insert([
            'id' => 1,
            'iso' => 'ID',
            'name' => 'INDONESIA',
            'nicename' => 'Indonesia',
            'iso3' => 'IDN',
            'numcode' => 360,
            'phonecode' => '62',
            'flag' => 'http://cdn-apps.nusapay.co.id/ygu8h37JIChrymPXsDhHa4KXXniB9c.png',
            'default' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        DB::table('accounts.countries')->insert([
            'id' => 2,
            'iso' => 'PH',
            'name' => 'PHILIPPINES',
            'nicename' => 'Philippines',
            'iso3' => 'PHL',
            'numcode' => 608,
            'phonecode' => '63',
            'flag' => 'http://cdn-apps.nusapay.co.id/GMva1clPuITyzmUDS4r3TN2gwLoC05.png',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.countries')->insert([
            'id' => 3,
            'iso' => 'VN',
            'name' => 'VIETNAM',
            'nicename' => 'Vietnam',
            'iso3' => 'VNM',
            'numcode' => 704,
            'phonecode' => '84',
            'flag' => 'http://cdn-apps.nusapay.co.id/ElHMdXidbRWwOp0zliX0476Od3k9Eq.png',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.countries')->insert([
            'id' => 4,
            'iso' => 'MY',
            'name' => 'MALAYSIA',
            'nicename' => 'Malaysia',
            'iso3' => 'MYS',
            'numcode' => 458,
            'phonecode' => '60',
            'flag' => 'http://cdn-apps.nusapay.co.id/jksJtw66KGDGSDVM7am9OcjtsOI6VY.png',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.countries')->insert([
            'id' => 5,
            'iso' => 'SG',
            'name' => 'SINGAPORE',
            'nicename' => 'Singapore',
            'iso3' => 'SGP',
            'numcode' => 702,
            'phonecode' => '65',
            'flag' => 'http://cdn-apps.nusapay.co.id/bhXeUoNkJc3OKRiZ2cXEZ2vfnoZFPW.png',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.countries')->insert([
            'id' => 6,
            'iso' => 'TH',
            'name' => 'THAILAND',
            'nicename' => 'Thailand',
            'iso3' => 'THA',
            'numcode' => 764,
            'phonecode' => '66',
            'flag' => 'http://cdn-apps.nusapay.co.id/7rpCOGIwFPBG0eTFZIXrRxFRkmPz86.png',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.countries')->insert([
            'id' => 7,
            'iso' => 'US',
            'name' => 'UNITED STATES',
            'nicename' => 'United States',
            'iso3' => 'USA',
            'numcode' => 840,
            'phonecode' => '1',
            'flag' => 'https://app-static.nusapay.co.id/flags/us.png',
            'default' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
