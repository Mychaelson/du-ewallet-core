<?php

namespace Database\Seeders\Wallet;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet\SwitchingFeeBanks;

class SwitchingFeeBanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/doc/switchingFeeBanks.csv"), "r");
        SwitchingFeeBanks::truncate();
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                SwitchingFeeBanks::create([
                    // "id" => $data['0'],
                    "bank_from" => $data['1'],
                    "bank_to" => $data['2'],
                    "fee" => $data['3'],
                    "cgs" => $data['4'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
