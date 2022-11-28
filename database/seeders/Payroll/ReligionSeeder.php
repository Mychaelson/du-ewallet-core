<?php

namespace Database\Seeders\Payroll;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $religions = [
            'islam',
            'kristen',
            'katolik',
            'hindu',
            'buddha',
            'konghucu',
        ];

        foreach ($religions as $religion) {
            DB::table('payroll.religions')->insert([
                'name' => str($religion)->title(),
                'slug' => str($religion)->slug(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
