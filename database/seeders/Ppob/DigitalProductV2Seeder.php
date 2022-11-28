<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ppob\ProductV2;

class DigitalProductV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/doc/productDigital.csv"), "r");
        ProductV2::truncate();
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                ProductV2::create([
                    'code' => $data['0'],
                    'name' => $data['1'],
                    'slug' => $data['2'],
                    'description' => $data['3'],
                    'provider' => $data['4'],
                    'category_id' => $data['5'],
                    'denom' => $data['6'],
                    'price_sell' => $data['7'],
                    'price_buy' => $data['8'],
                    'admin_fee' => $data['9'],
                    'status' => $data['10'],
                    'service_id' => $data['11'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
