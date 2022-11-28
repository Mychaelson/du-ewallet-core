<?php

namespace Database\Seeders\Payroll;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payroll.employee_status')->insert([
            'id' => 1,
            'name' => 'PKWTT (Permanen)',
            'slug' => 'permanen',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('payroll.employee_status')->insert([
            'id' => 2,
            'name' => 'PKWT (Kontrak)',
            'slug' => 'kontrak',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
