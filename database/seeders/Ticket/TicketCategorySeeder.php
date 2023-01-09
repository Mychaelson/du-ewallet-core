<?php

namespace Database\Seeders\Ticket;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket\Category;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        $csvFile = fopen(base_path("database/doc/ticketCategory.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Category::create([
                    'id' => $data['0'],
                    'name' => $data['1'],
                    'parent' => $data['2'],
                    'scope' => $data['3'],
                    'tts' => $data['4'],
                    'priority' => $data['5'],
                    'activity' => $data['6'],
                    'status' => $data['7'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
