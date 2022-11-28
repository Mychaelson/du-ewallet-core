<?php

namespace Database\Seeders\Accounts;

use App\Models\Accounts\BankInstruction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsBankInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.p
     *
     * @return void
     */
    public function run()
    {
        BankInstruction::truncate();

        $csvFile = fopen(base_path("database/doc/bankInstruction.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                BankInstruction::create([
                    "id"                    => $data['0'],
                    "transaction"           => $data['1'],
                    "method"                => $data['2'],
                    "title"                 => $data['3'],
                    "lang"                  => $data['4'],
                    "bank_code"             => $data['5'],
                    "bank_id"               => $data['6'],
                    "created_at"            => date('Y-m-d H:i:s'),
                    "updated_at"            => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
  
        fclose($csvFile);
    }
}
