<?php

namespace Database\Seeders\Accounts;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.roles')->insert([
            'id' => 1,
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Super User',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('accounts.roles')->insert([
            'id' => 2,
            'name' => 'Operator',
            'slug' => 'operator',
            'description' => 'Operator User',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
