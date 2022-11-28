<?php

namespace Database\Seeders\Lang;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProjectVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lang.project_version')->insert([
            
            [
                'id' => '1',
                'project_id' => '1',
                'project_version' => '1',
                'last_update' => '2020-12-29 07:03:24',
                'status' => '1',
                'last_status_date' => '2020-12-29 07:03:24',
           ]
            
                
        ]);
    }
}
