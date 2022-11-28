<?php

namespace Database\Seeders\Payroll;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payroll.status')->insert([
            'id' => 0,
            'name' => 'Canceled',
            'slug' => 'canceled',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('payroll.status')->insert([
            'id' => 1,
            'name' => 'Draft',
            'slug' => 'draft',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('payroll.status')->insert([
            'id' => 2,
            'name' => 'Waiting Approval',
            'slug' => 'waiting-approval',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('payroll.status')->insert([
            'id' => 3,
            'name' => 'Pending',
            'slug' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('payroll.status')->insert([
            'id' => 4,
            'name' => 'Partially Done',
            'slug' => 'partially-done',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('payroll.status')->insert([
            'id' => 5,
            'name' => 'Approved',
            'slug' => 'approved',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
