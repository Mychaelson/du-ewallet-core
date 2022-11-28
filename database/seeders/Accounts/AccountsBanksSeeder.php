<?php

namespace Database\Seeders\Accounts;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AccountsBanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.banks')->insert([
            'id' => 1,
            'code' => '014',
            'name' => 'BANK BCA',
            'image' => 'http://media.nusapay.io/upload/6yGLHdMMag3Bi3Ri6sKjn5YWqSXW2w.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 2,
            'code' => '008',
            'name' => 'BANK MANDIRI',
            'image' => 'http://media.nusapay.io/upload/9NCoyuCbotLQZYtCJCmQsVzM7h47wG.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 3,
            'code' => '009',
            'name' => 'BANK BNI',
            'image' => 'http://media.nusapay.io/upload/Ltr5I5asWioX3Eq6b8AgIpYEJJF2HE.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 4,
            'code' => '002',
            'name' => 'BANK BRI',
            'image' => 'http://media.nusapay.io/upload/ece4UBpS2HSN5wkrmqlHK6bciaLRWj.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 5,
            'code' => '022',
            'name' => 'BANK CIMB NIAGA',
            'image' => 'http://media.nusapay.io/upload/Y8HMUbb8unT1PSqMwVbX0H0JmRWioO.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 6,
            'code' => '427',
            'name' => 'BANK BNI SYARIAH',
            'image' => 'http://cdn-apps.nusapay.co.id/CAaZqBcQsMujxim58LLruc1rYe7t3O.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 7,
            'code' => '451',
            'name' => 'BANK MANDIRI SYARIAH',
            'image' => 'http://cdn-apps.nusapay.co.id/wQb9zNmMEu8yCwme0Kn1xkfLNeOh27.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 8,
            'code' => '147',
            'name' => 'BANK MUAMALAT',
            'image' => 'http://cdn-apps.nusapay.co.id/6tHmLGJlMJMLA0Ta4tm8v8KWCVprxE.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 9,
            'code' => '213',
            'name' => 'BANK BTPN',
            'image' => 'http://cdn-apps.nusapay.co.id/HBUdE9aDTDqg7Fnv44RhYUx8UksmH6.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 10,
            'code' => '022',
            'name' => 'BANK CIMB NIAGA SYARIAH',
            'image' => 'http://cdn-apps.nusapay.co.id/zeIqcoMCadQjDib2XloVVkk9r4LVst.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 11,
            'code' => '422',
            'name' => 'BANK BRI SYARIAH',
            'image' => 'http://cdn-apps.nusapay.co.id/wONup6kVfZje3RnS27l5wEYGJzwhvJ.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 12,
            'code' => '200',
            'name' => 'BANK BTN',
            'image' => 'http://cdn-apps.nusapay.co.id/0jlV08tzxTT9OzKo1cxjNWOAgmHjSj.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 13,
            'code' => '013',
            'name' => 'BANK PERMATA',
            'image' => 'http://cdn-apps.nusapay.co.id/7NB6Jxx6McaGrOwXz7SbeVrGqXqguE.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 14,
            'code' => '011',
            'name' => 'BANK DANAMON',
            'image' => 'http://cdn-apps.nusapay.co.id/NLdOpr37pfwg24mWjr1rNx82ghxCx1.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 15,
            'code' => '016',
            'name' => 'BANK MAYBANK',
            'image' => 'http://cdn-apps.nusapay.co.id/iL5iOV9Jythr3IJ3sGLkddmnlvdwTQ.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 16,
            'code' => '426',
            'name' => 'BANK MEGA',
            'image' => 'http://cdn-apps.nusapay.co.id/JAywS0Va2XywHN9l0gzNGAiaigREDd.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 17,
            'code' => '153',
            'name' => 'BANK SINARMAS',
            'image' => 'http://cdn-apps.nusapay.co.id/9gzHbjxJXRPKpJfEAf5bAjg0mdkBqg.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 18,
            'code' => '950',
            'name' => 'BANK COMMONWEALTH',
            'image' => 'http://cdn-apps.nusapay.co.id/69AvG4SGiYwuWj8DpJ20tIEVpo8idO.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 19,
            'code' => '028',
            'name' => 'BANK OCBC NISP',
            'image' => 'http://cdn-apps.nusapay.co.id/DKkXIWTKzP6YHAdJTfhpHqfSbZm1dD.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 20,
            'code' => '441',
            'name' => 'BANK BUKOPIN',
            'image' => 'http://cdn-apps.nusapay.co.id/YwInr9THhFEUoFj1qgILUMmSHesmCV.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 21,
            'code' => '536',
            'name' => 'BANK BCA SYARIAH',
            'image' => 'http://cdn-apps.nusapay.co.id/hlM7x5EvXNLToRROO1DbueRd2ybOeQ.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 22,
            'code' => '031',
            'name' => 'CITIBANK',
            'image' => 'http://cdn-apps.nusapay.co.id/rxDDyTVVgizalXzUFkm47LKEjBBDAz.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 23,
            'code' => '110',
            'name' => 'BANK BJB',
            'image' => 'http://cdn-apps.nusapay.co.id/YQgkvpb8lMilYfoOwnwFDMym7FiEWa.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 24,
            'code' => '111',
            'name' => 'BANK DKI',
            'image' => 'http://cdn-apps.nusapay.co.id/YETzSI2x91tPw3GjC54Bp7zxPBcsiD.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 25,
            'code' => '041',
            'name' => 'BANK HSBC',
            'image' => 'http://cdn-apps.nusapay.co.id/9fDCBFcasUoALnXTt7RPZAcLdVXlVo.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 26,
            'code' => '019',
            'name' => 'BANK PANIN',
            'image' => 'http://cdn-apps.nusapay.co.id/yn9DKPMyPlG1XW60muF41ohbHy6oSO.png',
            'account_type' => 'BA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 27,
            'code' => '8611',
            'name' => 'BANK BNI VA',
            'image' => 'http://media.nusapay.io/upload/Ltr5I5asWioX3Eq6b8AgIpYEJJF2HE.png',
            'account_type' => 'VA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 28,
            'code' => '9639',
            'name' => 'BANK CIMB NIAGA VA',
            'image' => 'http://media.nusapay.io/upload/Y8HMUbb8unT1PSqMwVbX0H0JmRWioO.png',
            'account_type' => 'VA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 29,
            'code' => '7111',
            'name' => 'BANK PERMATA VA',
            'image' => 'http://cdn-apps.nusapay.co.id/7NB6Jxx6McaGrOwXz7SbeVrGqXqguE.png',
            'account_type' => 'VA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('accounts.banks')->insert([
            'id' => 30,
            'code' => '89811',
            'name' => 'BANK MANDIRI VA',
            'image' => 'http://media.nusapay.io/upload/9NCoyuCbotLQZYtCJCmQsVzM7h47wG.png',
            'account_type' => 'VA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
