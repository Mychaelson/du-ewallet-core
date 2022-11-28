<?php

namespace Database\Seeders\Feed;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FeedSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.feed_source')->insert([
            'id' => 1,
            'feed_source' => 'merahputih.com',
            'source_url' => 'https://merahputih.com/feed.xml',
            'latest_data' => '[{"guid":"https:\/\/merahputih.com\/post\/read\/prediksi-timnas-indonesia-u-23-vs-vietnam-ayo-garuda-bawa-pulang-medali-emas","title":"Prediksi Timnas Indonesia U-23 Vs Vietnam: Ayo Garuda Bawa Pulang Medali Emas!","link":"https:\/\/merahputih.com\/post\/read\/prediksi-timnas-indonesia-u-23-vs-vietnam-ayo-garuda-bawa-pulang-medali-emas","category":["Olahraga"],"description":"Ini menjadi ujian terakhir pasukan Indra Sjafri untuk menuntaskan puasa medali emas SEA Games, yang terakhir kali diraih 28 tahun silam.","enclosure":{"@attributes":{"url":"https:\/\/merahputih.com\/media\/fa\/c3\/63\/fac3632a67fe9412a4a167128809395e.jpeg","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih","image":{"url":"https:\/\/merahputih.com\/theme\/default\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/","title":"MerahPutih"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/diancam-digebuki-henry-yosodiningrat-andi-arief-saya-tunggu","title":"Diancam Digebuki Henry Yosodiningrat, Andi Arief: Saya Tunggu!","link":"https:\/\/merahputih.com\/post\/read\/diancam-digebuki-henry-yosodiningrat-andi-arief-saya-tunggu","category":["Indonesia","Berita"],"description":"Andi Arief mengaku tidak ahli baku hantam dan menunggu kedatangan Henry Yosodiningrat ke rumah. Ia tidak akan melapor ke polisi.","enclosure":{"@attributes":{"url":"https:\/\/merahputih.com\/media\/7d\/52\/ad\/7d52ad238a5733f9cd6c2858ec5e7c74.jpg","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih","image":{"url":"https:\/\/merahputih.com\/theme\/default\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/","title":"MerahPutih"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/pimpinan-dpr-usul-vonis-mati-pertama-untuk-koruptor-duit-bencana","title":"Pimpinan DPR Usul Vonis Mati Pertama untuk Koruptor Duit Bencana","link":"https:\/\/merahputih.com\/post\/read\/pimpinan-dpr-usul-vonis-mati-pertama-untuk-koruptor-duit-bencana","category":["Indonesia","Berita"],"description":"Penyelewengan dana bantuan bencana alam sebagai bentuk tindak pidana korupsi berat.","enclosure":{"@attributes":{"url":"https:\/\/merahputih.com\/media\/48\/20\/e6\/4820e66903f16ce3c26fc01e17e03673.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih","image":{"url":"https:\/\/merahputih.com\/theme\/default\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/","title":"MerahPutih"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/pecah-hari-kedua-singapore-comic-con-berlangsung-meriah","title":"Pecah, Hari Kedua Singapore Comic Con Berlangsung Meriah","link":"https:\/\/merahputih.com\/post\/read\/pecah-hari-kedua-singapore-comic-con-berlangsung-meriah","category":["Fun","Hiburan & Gaya Hidup"],"description":"Para pengunjung puas dengan rangakaian acara Singapore Comic Con 2019.","enclosure":{"@attributes":{"url":"https:\/\/merahputih.com\/media\/8d\/bf\/19\/8dbf19a7b22303c82c880c6e73873cee.JPG","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih","image":{"url":"https:\/\/merahputih.com\/theme\/default\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/","title":"MerahPutih"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/yang-harus-diubah-dari-kampus-gue","title":"Yang Harus Diubah dari Kampus Gue!","link":"https:\/\/merahputih.com\/post\/read\/yang-harus-diubah-dari-kampus-gue","category":["Hiburan & Gaya Hidup"],"description":"Keinginan mendasar generasi Z kepada kampusnya enggak muluk","enclosure":{"@attributes":{"url":"https:\/\/merahputih.com\/media\/7d\/72\/f4\/7d72f4916b621e15bf6802026c06d853.jpg","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih","image":{"url":"https:\/\/merahputih.com\/theme\/default\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/","title":"MerahPutih"}}}]',
            'latest_fetch' => '2019-12-10',
            'api_detail' => 'https://merahputih.com/api/post/{slug}',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
