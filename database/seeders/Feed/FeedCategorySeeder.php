<?php

namespace Database\Seeders\Feed;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FeedCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.feed_category')->insert([
            'id' => 1,
            'category_name' => 'Berita',
            'category_type' => 1,
            'category_id' => 0,
            'category_data' => '[{"guid":"https:\/\/merahputih.com\/post\/read\/bamsoet-akui-harmoko-panutan-banyak-kader-partai-golkar","title":"Bamsoet Akui Harmoko Panutan Banyak Kader Partai Golkar","link":"https:\/\/merahputih.com\/post\/read\/bamsoet-akui-harmoko-panutan-banyak-kader-partai-golkar","category":["Indonesia","Berita"],"description":"Harmoko yang pernah menjabat sebagai ketua umum Golkar menderita sakit sejak beberapa tahun lalu","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/30720260e09cae7710e22b9eb0ffbf7e.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/mantan-menteri-penerangan-era-soeharto-harmoko-meninggal-dunia","title":"Mantan Menteri Penerangan Era Soeharto Harmoko Meninggal Dunia","link":"https:\/\/merahputih.com\/post\/read\/mantan-menteri-penerangan-era-soeharto-harmoko-meninggal-dunia","category":["Indonesia","Berita"],"description":"Harmoko meninggal di RSPAD Gatot Soebroto","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/afb94e6f6c2cf1a48b7f32da7f25e188.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/monas-disulap-jadi-posko-oxygen-rescue","title":"Monas Disulap Jadi Posko Oxygen Rescue","link":"https:\/\/merahputih.com\/post\/read\/monas-disulap-jadi-posko-oxygen-rescue","category":["Indonesia","Berita"],"description":"Bantuan dapat diberikan dalam bentuk tabung atau isi oksigennya","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/5c6b93761cfa9788004c90ec3bfc4929.jpg","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}}]',
            'latest_feed' => '2021-07-05',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.feed_category')->insert([
            'id' => 2,
            'category_name' => 'Entertainment',
            'category_type' => 1,
            'category_id' => 0,
            'category_data' => '[{"guid":"https:\/\/merahputih.com\/post\/read\/bamsoet-akui-harmoko-panutan-banyak-kader-partai-golkar","title":"Bamsoet Akui Harmoko Panutan Banyak Kader Partai Golkar","link":"https:\/\/merahputih.com\/post\/read\/bamsoet-akui-harmoko-panutan-banyak-kader-partai-golkar","category":["Indonesia","Berita"],"description":"Harmoko yang pernah menjabat sebagai ketua umum Golkar menderita sakit sejak beberapa tahun lalu","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/30720260e09cae7710e22b9eb0ffbf7e.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/mantan-menteri-penerangan-era-soeharto-harmoko-meninggal-dunia","title":"Mantan Menteri Penerangan Era Soeharto Harmoko Meninggal Dunia","link":"https:\/\/merahputih.com\/post\/read\/mantan-menteri-penerangan-era-soeharto-harmoko-meninggal-dunia","category":["Indonesia","Berita"],"description":"Harmoko meninggal di RSPAD Gatot Soebroto","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/afb94e6f6c2cf1a48b7f32da7f25e188.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/monas-disulap-jadi-posko-oxygen-rescue","title":"Monas Disulap Jadi Posko Oxygen Rescue","link":"https:\/\/merahputih.com\/post\/read\/monas-disulap-jadi-posko-oxygen-rescue","category":["Indonesia","Berita"],"description":"Bantuan dapat diberikan dalam bentuk tabung atau isi oksigennya","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/5c6b93761cfa9788004c90ec3bfc4929.jpg","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}}]',
            'latest_feed' => '2021-07-05',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.feed_category')->insert([
            'id' => 3,
            'category_name' => 'Olahraga',
            'category_type' => 1,
            'category_id ' => 0,
            'category_data' => '[{"guid":"https:\/\/merahputih.com\/post\/read\/bamsoet-akui-harmoko-panutan-banyak-kader-partai-golkar","title":"Bamsoet Akui Harmoko Panutan Banyak Kader Partai Golkar","link":"https:\/\/merahputih.com\/post\/read\/bamsoet-akui-harmoko-panutan-banyak-kader-partai-golkar","category":["Indonesia","Berita"],"description":"Harmoko yang pernah menjabat sebagai ketua umum Golkar menderita sakit sejak beberapa tahun lalu","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/30720260e09cae7710e22b9eb0ffbf7e.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/mantan-menteri-penerangan-era-soeharto-harmoko-meninggal-dunia","title":"Mantan Menteri Penerangan Era Soeharto Harmoko Meninggal Dunia","link":"https:\/\/merahputih.com\/post\/read\/mantan-menteri-penerangan-era-soeharto-harmoko-meninggal-dunia","category":["Indonesia","Berita"],"description":"Harmoko meninggal di RSPAD Gatot Soebroto","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/afb94e6f6c2cf1a48b7f32da7f25e188.png","length":"1054","type":"image\/png"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}},{"guid":"https:\/\/merahputih.com\/post\/read\/monas-disulap-jadi-posko-oxygen-rescue","title":"Monas Disulap Jadi Posko Oxygen Rescue","link":"https:\/\/merahputih.com\/post\/read\/monas-disulap-jadi-posko-oxygen-rescue","category":["Indonesia","Berita"],"description":"Bantuan dapat diberikan dalam bentuk tabung atau isi oksigennya","enclosure":{"@atrributes":{"url":"https:\/\/newsfeed.um0phbmlhwn0qyay58pf.click\/contents\/assets\/images\/5c6b93761cfa9788004c90ec3bfc4929.jpg","length":"1054","type":"image\/jpeg"}},"identity":{"title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita","image":{"url":"https:\/\/merahputih.com\/theme\/mp2018\/static\/image\/logo\/feed.jpg","link":"https:\/\/merahputih.com\/post\/category\/berita","title":"MerahPutih \u00bb Post \u00bb Category \u00bb Berita"}}}]',
            'latest_feed' => '2021-07-05',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
