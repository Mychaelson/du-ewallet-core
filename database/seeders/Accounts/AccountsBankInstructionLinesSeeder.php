<?php

namespace Database\Seeders\Accounts;

use App\Models\Accounts\BankInstructionLines;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsBankInstructionLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankInstructionLines::truncate();

        $csvFile = fopen(base_path("database/doc/bankInstructionLines.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                BankInstructionLines::create([
                    "id"                    => $data['0'],
                    "instruction_id"        => $data['1'],
                    "title"                 => $data['2'],
                    "steps"                 => $data['3'],
                    "step_type"             => $data['4'],
                    "step_value"            => $data['5'],
                    "lang"                  => $data['6'],
                    "created_at"            => date('Y-m-d H:i:s'),
                    "updated_at"            => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
