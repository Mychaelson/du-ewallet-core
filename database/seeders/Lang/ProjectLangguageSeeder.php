<?php

namespace Database\Seeders\Lang;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectLangguageSeeder extends Seeder
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
        
        DB::table('lang.project_language')->insert([
           
            [
                'id' => 1,
                'hl' => 'en',
                'flag' => 'http://media.'.$domain.'/image/R9O1wWH907NNRj9c2fKLm6e7Rsz3XQ.png',
            ],
            [
                'id' => 2,
                'hl' => 'in',
                'flag' => 'http://media.'.$domain.'/image/tCMBzh7K3XNTMbFcLoovKVt2PubLgA.png',
            ]
                
        ]);
    }
}
