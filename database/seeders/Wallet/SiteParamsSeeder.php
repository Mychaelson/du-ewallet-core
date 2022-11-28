<?php

namespace Database\Seeders\Wallet;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet\SiteParam;

class SiteParamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteParam::truncate();

        $csvFile = fopen(base_path("database/doc/walletSiteParam.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                SiteParam::create([
                    "name" => $data['0'],
                    "type" => $data['1'],
                    "group" => $data['2'],
                    "value" => $data['3'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
