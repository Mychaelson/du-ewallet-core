<?php

namespace Database\Seeders\Notifications;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \DB;
use \Str;

class Notifications extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Str::random(10)
      DB::table('notif.notifications')->insert([
        'id' => '0000350d-8faf-42ea-b5e4-'.Str::random(12),
        'type' => 'App\Notifications\DatabaseNotification',
        'notifiable_id' => 26,
        'notifiable_type' => 'App\OAuth\User',
        'data' => '{"activity":"wallet-payment-merchant","title":"Pembayaran invoice #100000001789 sudah diterima","content":"Pembayaran invoice #100000001789 sudah diterima","url":"","data":{"data":{"id":5214,"amount":203000,"fee":0,"total":203000,"spending":false,"label":{"id":2},"ncash":"0.00","balance":10470025,"description":"Pembelian Token Listrik 200,000","reff":null,"type":6,"topup":null,"transfer":null,"withdraw":null,"payment":{"id":1187,"invoice":"100000001789","created":"2018-06-06T07:08:04+07:00"},"refund":null,"cashback":null,"status":3,"reason":null,"updated":"2018-06-06T07:08:04+07:00","created":"2018-06-06T07:08:04+07:00"}}}',
        'read_at' => null,
        'category' => 'Struk Pulsa',
        'icon' => 'https://cdn.nusapay.co.id/upload/PULSA.png',
        'merchant_id' => 0
      ]);
    }
}
