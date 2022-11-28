<?php

namespace Database\Seeders\Media;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.groups')->insert([
            'id' => 2,
            'name' => 'Logo Ext',
            'description' => '',
            'user_id' => '7',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.groups')->insert([
            'id' => 6,
            'name' => 'Flag Asean',
            'description' => 'flag asean for register & login',
            'user_id' => '7',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);


        DB::table('accounts.groups')->insert([
            'id' => 7,
            'name' => 'Merchant Documents',
            'description' => 'flag asean for register & login',
            'user_id' => '118',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);


        DB::table('accounts.groups')->insert([
            'id' =>8,
            'name' => 'Bank Logo',
            'description' => 'bank logo',
            'user_id' => '9',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }
}
