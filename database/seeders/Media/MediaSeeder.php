<?php

namespace Database\Seeders\Media;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.media')->insert([
            'id'=> 1,
            'user_id' => 8,
            'group_id' => 2,
            'name' => 'bpjs.jpg',
            'filename' => 'cwYgAvHiPF7lkvqMCzJ9gVspbMgf6T.jpg',
            'extension' => 'png',
            'mimetype' => 'image/png',
            'filesize' => 99311,
            'filepath' => 'http://cdn-apps.nusapay.co.id/SxWfxBc10E60uOM6Fw3mNGEWUy8BJE.png',
            'url' => 'http://cdn-apps.nusapay.co.id/SxWfxBc10E60uOM6Fw3mNGEWUy8BJE.png',
            'thumb' => '',
            'type' => '',
            'disk' => 'awss3',
            'description' => '',
            'publish' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('accounts.media')->insert([
            'id'=>2,
            'user_id' => 9,
            'group_id' => 2,
            'name' => 'JPEG_20180401_212154_1939030455.jpg',
            'filename' => 'LNSg07CdjAxLuePa8a7d5LcecNKGQK.jpg',
            'extension' => 'png',
            'mimetype' => 'image/png',
            'filesize' => 99311,
            'filepath' => '/home/apps/media/public/upload',
            'url' => 'https://cdn.nusapay.co.id/upload/LNSg07CdjAxLuePa8a7d5LcecNKGQK.jpg',
            'thumb' => '',
            'type' => '',
            'disk' => 'awss3',
            'description' => '',
            'publish' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.media')->insert([
            'id'=>3,
            'user_id' => 11,
            'group_id' => 2,
            'name' => 'avatar.jpg',
            'filename' => 'GtRW6ypTl5bZ5YAdKGJLmsGrD817s2.jpg',
            'extension' => 'ppg',
            'mimetype' => 'image/*',
            'filesize' => 99311,
            'filepath' => '/home/apps/media/public/upload',
            'url' => 'https://cdn.nusapay.co.id/upload/LNSg07CdjAxLuePa8a7d5LcecNKGQK.jpg',
            'thumb' => '',
            'type' => '',
            'disk' => 'awss3',
            'description' => '',
            'publish' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
