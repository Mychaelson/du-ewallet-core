<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings\SiteParams;

class SiteParamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteParams::truncate();

        $csvFile = fopen(base_path("database/doc/siteParam.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                SiteParams::create([
                    "id" => $data['0'],
                    "name" => $data['1'],
                    "type" => $data['2'],
                    "group" => $data['3'],
                    "value" => $data['4'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
