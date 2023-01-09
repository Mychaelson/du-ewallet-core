<?php

namespace Database\Seeders\Notifications;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotifCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notif.notif_category')->insert([
            'category' => 'Customer Care',
            'icon' => 'https://dupay-dev.s3.ap-southeast-3.amazonaws.com/Logo+dupay+light.png',
            'last_activity' => 'last_activity',
            'activity' => 'ticket_support',
            'namespace' => 'App\Http\Controllers\Ticket'
        ]);

        DB::table('notif.notif_category')->insert([
            'category' => 'Transaction',
            'icon' => 'http://cdn-apps.nusapay.co.id/nhWJH60j0DgvNsKXuaRDCYjkxKPLF2.png',
            'last_activity' => 'transaction_activity',
            'activity' => 'transaction_invoice',
            'namespace' => 'App\Http\Controllers\Payment'
        ]);
    }
}
