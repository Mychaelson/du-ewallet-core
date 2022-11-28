<?php

namespace Database\Seeders\Accounts;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accounts\CompanyBankAccounts;

class CompanyBankAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyBankAccounts::truncate();

        $csvFile = fopen(base_path("database/doc/companyAccountBank.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                CompanyBankAccounts::create([
                    "id"                    => $data['0'],
                    "bank_id"               => $data['1'],
                    "account_name"          => $data['2'],
                    "account_number"        => $data['3'],
                    "payment_gateway"       => $data['4'],
                    "payment_method_code"   => $data['5'],
                    "is_active"             => $data['6'],
                    "is_virtual"            => $data['7'],
                    "created_at"            => date('Y-m-d H:i:s'),
                    "updated_at"            => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
