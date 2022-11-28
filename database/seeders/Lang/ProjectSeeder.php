<?php

namespace Database\Seeders\Lang;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = config('company.info');
        $pt = $company['name'];
        $brand = $company['brand'];
        $domain = $company['domain'];
        DB::table('lang.project')->insert([
            
            [
                'id' => '1',
                'project_uid' => 'main-apps',
                'project_description' => 'Main Apps',
                'project_image' => 'http://url.to/image.png',
                'status' => '1',
                'last_status_date' => null,
            ],
            [
                'id' => '2',
                'project_uid' => 'project-app',
                'project_description' => 'Project App',
                'project_image' => 'http://media.'.$domain.'/image/AvZqZtqktdTbZD6EhXuK2YqJWGFNCr.png',
                'status' => '0',
                'last_status_date' => '2020-12-21 07:49:39',
            ],
            [
                'id' => '3',
                'project_uid' => 'project-tes',
                'project_description' => 'tes project',
                'project_image' => 'http://media.'.$domain.'/image/bVFXRqXKdOJhiuF6XjDZlwHNnX0pux.png',
                'status' => '0',
                'last_status_date' => '2020-12-21 07:43:18',
            ],
            [
                'id' => '4',
                'project_uid' => 'project-tez',
                'project_description' => 'tez project',
                'project_image' => 'http://media.'.$domain.'/image/Zgps4lj9roruD3CMJauV3YrZ7jih35.jpg',
                'status' => '0',
                'last_status_date' => '2020-12-21 08:06:10',
            ],
            [
                'id' => '5',
                'project_uid' => 'main-app',
                'project_description' => 'Main App',
                'project_image' => 'http://url.to/image.png',
                'status' => '0',
                'last_status_date' => '2021-04-30 07:27:34',
            ]
                
        ]);
    }
}
