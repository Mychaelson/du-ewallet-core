<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ppob\ProductService;

class DigitalProductServiceSeederV2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/doc/productDigitalService.csv"), "r");
        ProductService::truncate();
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                ProductService::create([
                    'product_code' => $data['0'],
                    'service_id' => $data['1'],
                    'base_price' => $data['2'],
                    'admin_fee' => $data['3'],
                    'code' => $data['4'],
                    'status' => $data['5'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
