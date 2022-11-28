<?php

namespace Database\Seeders\Docs;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HelpCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('docs.help_category')->insert([
            [
                'id' => '1',
                'user' => '5',
                'name' => 'Akun Saya',
                'group' => 'user',
                'slug' => 'my-account',
                'locale' => 'id-ID',
                'icon' => 'https://banner2.kisspng.com/20180405/osq/kisspng-login-google-account-computer-icons-user-activity-5ac6bbe6ecba00.2369522615229736709696.jpg',
            ],
            [
                'id' => '2',
                'user' => '5',
                'name' => 'Dompet Saya / Nusaku',
                'group' => 'user',
                'slug' => 'ewallet',
                'locale' => 'id-ID',
                'icon' => 'https://freeiconshop.com/wp-content/uploads/edd/wallet-outline.png',
            ],
            [
                'id' => '4',
                'user' => '5',
                'name' => 'Pembelian Produk',
                'group' => 'user',
                'slug' => 'purchase',
                'locale' => 'id-ID',
                'icon' => 'http://cdn.onlinewebfonts.com/svg/img_129812.png',
            ],
            [
                'id' => '6',
                'user' => '5',
                'name' => 'My Account',
                'group' => 'user',
                'slug' => 'my-account',
                'locale' => 'en-US',
                'icon' => 'https://banner2.kisspng.com/20180405/osq/kisspng-login-google-account-computer-icons-user-activity-5ac6bbe6ecba00.2369522615229736709696.jpg',
            ],
            [
                'id' => '7',
                'user' => '5',
                'name' => 'My Wallet',
                'group' => 'user',
                'slug' => 'my-wallet',
                'locale' => 'en-US',
                'icon' => 'https://freeiconshop.com/wp-content/uploads/edd/wallet-outline.png',
            ],
            [
                'id' => '10',
                'user' => '5',
                'name' => 'Layanan Pelanggan',
                'group' => 'user',
                'slug' => 'layanan-pelanggan',
                'locale' => 'id-ID',
                'icon' => 'https://static.thenounproject.com/png/6704-200.png',
            ]
        ]);
    }
}
