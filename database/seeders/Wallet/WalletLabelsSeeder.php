<?php

namespace Database\Seeders\Wallet;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet\WalletLabels;

class WalletLabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallet.wallet_labels')->insert([
            'name' => 'Cash Out',
            'icon' => 'https://cdn.nusapay.co.id/icon/icon-cashout.png',
            'background' => 'https://media.um0phbmlhwn0qyay58pf.click/image//MFAIXNZqrSEnoCaTcYIeXsVBbGM9xA.png',
            'color' => '#ffffff',
            'spending' => '1',
            'default' => '1',
            'organization' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('wallet.wallet_labels')->insert([
            'name' => 'Cash in ',
            'icon' => '',
            'background' => '',
            'color' => '#ffffff',
            'spending' => '0',
            'default' => '1',
            'organization' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('wallet.wallet_labels')->insert([
            'name' => 'Love',
            'icon' => 'https://cdn.nusapay.co.id/icon/icon-love.png',
            'background' => 'https://media.um0phbmlhwn0qyay58pf.click/image//fgij3ySLUJs5W6uB9FOxFStaXNn0Ob.png',
            'color' => '#ee585c',
            'spending' => '1',
            'default' => '1',
            'organization' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('wallet.wallet_labels')->insert([
            'name' => 'Meal','icon' => 'https://cdn.nusapay.co.id/icon/icon-meal.png',
            'background' => 'https://media.um0phbmlhwn0qyay58pf.click/image//yEIzsLcIr4AX9tNCIyVH5SVmRYjZMt.png',
            'color' => '#ff601d',
            'spending' => '1',
            'default' => '1',
            'organization' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('wallet.wallet_labels')->insert([
            'name' => 'Thanks','icon' => 'https://media.um0phbmlhwn0qyay58pf.click/image//5iCVOZNqlIcXsHiRNG7kiuKGOt6qoX.png',
            'background' => 'https://media.um0phbmlhwn0qyay58pf.click/image//jgxGd8FCKhF1E1AHnixsjWayMX9Njp.png',
            'color' => '#02b670',
            'spending' => '1',
            'default' => '1',
            'organization' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('wallet.wallet_labels')->insert([
            'name' => 'Tip',
            'icon' => 'https://cdn.nusapay.co.id/icon/icon-tip.png',
            'background' => 'https://media.um0phbmlhwn0qyay58pf.click/image//SvUQVR2LWil1Qi3Qy1Svg3LsRI3WZd.png',
            'color' => '#882a9d',
            'spending' => '1',
            'default' => '1',
            'organization' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
