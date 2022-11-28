<?php

namespace Database\Seeders\Ppob;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalProductServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppob.digital_product_services')->insert([
            
            [
                'product_id' => 1,
                'service_id' => 10,
                'base_price' => 5775.0,
                'admin_fee' => 0.0,
                'code' => 'S5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-05-13 21:05:10',
                'updated_at' => '2021-05-31 20:56:56'
            ],
            [
                'product_id' => 13,
                'service_id' => 10,
                'base_price' => 24925.0,
                'admin_fee' => 0.0,
                'code' => 'S25',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-05-13 21:12:08',
                'updated_at' => '2019-09-17 01:32:20'
            ],
            [
                'product_id' => 14,
                'service_id' => 10,
                'base_price' => 49575.0,
                'admin_fee' => 0.0,
                'code' => 'S50',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-05-13 21:13:38',
                'updated_at' => '2021-05-31 20:57:49'
            ],
            [
                'product_id' => 12,
                'service_id' => 10,
                'base_price' => 10295.0,
                'admin_fee' => 0.0,
                'code' => 'S10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-05-13 21:53:27',
                'updated_at' => '2021-05-31 20:56:46'
            ],
            [
                'product_id' => 18,
                'service_id' => 10,
                'base_price' => 196575.0,
                'admin_fee' => 0.0,
                'code' => 'S200',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-06-14 12:34:30',
                'updated_at' => '2019-08-07 23:55:42'
            ],
            [
                'product_id' => 113,
                'service_id' => 10,
                'base_price' => 5900.0,
                'admin_fee' => 0.0,
                'code' => 'X5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-14 15:22:04',
                'updated_at' => '2019-08-13 23:56:32'
            ],
            [
                'product_id' => 116,
                'service_id' => 10,
                'base_price' => 49575.0,
                'admin_fee' => 0.0,
                'code' => 'X50',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-14 15:37:25',
                'updated_at' => '2019-08-07 22:54:40'
            ],
            [
                'product_id' => 115,
                'service_id' => 10,
                'base_price' => 24825.0,
                'admin_fee' => 0.0,
                'code' => 'X25',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-14 15:38:43',
                'updated_at' => '2019-09-17 01:46:22'
            ],
            [
                'product_id' => 114,
                'service_id' => 10,
                'base_price' => 10735.0,
                'admin_fee' => 0.0,
                'code' => 'X10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-14 15:39:50',
                'updated_at' => '2021-04-13 21:05:30'
            ],
            [
                'product_id' => 117,
                'service_id' => 10,
                'base_price' => 98575.0,
                'admin_fee' => 0.0,
                'code' => 'X100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-14 15:42:17',
                'updated_at' => '2019-11-06 17:12:33'
            ],
            [
                'product_id' => 184,
                'service_id' => 10,
                'base_price' => 13350.0,
                'admin_fee' => 0.0,
                'code' => 'AIGO1',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:17:13',
                'updated_at' => '2018-08-25 06:17:13'
            ],
            [
                'product_id' => 185,
                'service_id' => 10,
                'base_price' => 22950.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:19:53',
                'updated_at' => '2019-08-06 16:43:21'
            ],
            [
                'product_id' => 186,
                'service_id' => 10,
                'base_price' => 29000.0,
                'admin_fee' => 0.0,
                'code' => 'AIGO3',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:22:48',
                'updated_at' => '2019-08-02 02:55:44'
            ],
            [
                'product_id' => 187,
                'service_id' => 10,
                'base_price' => 43550.0,
                'admin_fee' => 0.0,
                'code' => 'AIGO5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:25:53',
                'updated_at' => '2019-08-02 02:54:50'
            ],
            [
                'product_id' => 188,
                'service_id' => 10,
                'base_price' => 59250.0,
                'admin_fee' => 0.0,
                'code' => 'AIGO8',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:28:00',
                'updated_at' => '2019-08-02 02:56:19'
            ],
            [
                'product_id' => 189,
                'service_id' => 10,
                'base_price' => 29500.0,
                'admin_fee' => 0.0,
                'code' => 'XH30',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:35:39',
                'updated_at' => '2019-10-07 17:18:15'
            ],
            [
                'product_id' => 190,
                'service_id' => 10,
                'base_price' => 44075.0,
                'admin_fee' => 0.0,
                'code' => 'XH45',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:39:41',
                'updated_at' => '2018-12-30 12:59:39'
            ],
            [
                'product_id' => 191,
                'service_id' => 10,
                'base_price' => 54900.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:41:48',
                'updated_at' => '2019-09-02 17:47:53'
            ],
            [
                'product_id' => 192,
                'service_id' => 10,
                'base_price' => 90250.0,
                'admin_fee' => 0.0,
                'code' => 'XH90',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:43:27',
                'updated_at' => '2021-04-16 16:29:30'
            ],
            [
                'product_id' => 193,
                'service_id' => 10,
                'base_price' => 117000.0,
                'admin_fee' => 0.0,
                'code' => 'XH117',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:45:40',
                'updated_at' => '2019-09-02 17:50:09'
            ],
            [
                'product_id' => 194,
                'service_id' => 10,
                'base_price' => 161250.0,
                'admin_fee' => 0.0,
                'code' => 'XH162',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:47:50',
                'updated_at' => '2019-09-02 17:58:34'
            ],
            [
                'product_id' => 195,
                'service_id' => 10,
                'base_price' => 196750.0,
                'admin_fee' => 0.0,
                'code' => 'XH198',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:49:56',
                'updated_at' => '2019-09-02 18:03:21'
            ],
            [
                'product_id' => 196,
                'service_id' => 10,
                'base_price' => 19150.0,
                'admin_fee' => 0.0,
                'code' => 'SMV20',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:54:53',
                'updated_at' => '2019-08-02 03:14:39'
            ],
            [
                'product_id' => 197,
                'service_id' => 10,
                'base_price' => 31400.0,
                'admin_fee' => 0.0,
                'code' => 'SMV30',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:56:44',
                'updated_at' => '2021-03-18 17:38:49'
            ],
            [
                'product_id' => 198,
                'service_id' => 10,
                'base_price' => 53800.0,
                'admin_fee' => 0.0,
                'code' => 'SMV60',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 06:59:08',
                'updated_at' => '2019-08-02 03:15:06'
            ],
            [
                'product_id' => 199,
                'service_id' => 10,
                'base_price' => 74600.0,
                'admin_fee' => 0.0,
                'code' => 'SMV100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:04:53',
                'updated_at' => '2021-03-18 17:44:06'
            ],
            [
                'product_id' => 200,
                'service_id' => 10,
                'base_price' => 142600.0,
                'admin_fee' => 0.0,
                'code' => 'SMV150',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:07:03',
                'updated_at' => '2019-08-02 03:15:49'
            ],
            [
                'product_id' => 201,
                'service_id' => 10,
                'base_price' => 182700.0,
                'admin_fee' => 0.0,
                'code' => 'SMV200',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:08:37',
                'updated_at' => '2019-08-02 03:16:05'
            ],
            [
                'product_id' => 202,
                'service_id' => 10,
                'base_price' => 28650.0,
                'admin_fee' => 0.0,
                'code' => 'BO29',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:13:07',
                'updated_at' => '2018-08-25 07:13:07'
            ],
            [
                'product_id' => 203,
                'service_id' => 10,
                'base_price' => 48050.0,
                'admin_fee' => 0.0,
                'code' => 'BO49',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:16:12',
                'updated_at' => '2018-08-25 07:16:12'
            ],
            [
                'product_id' => 204,
                'service_id' => 10,
                'base_price' => 96600.0,
                'admin_fee' => 0.0,
                'code' => 'BO99',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:17:56',
                'updated_at' => '2018-08-25 07:17:56'
            ],
            [
                'product_id' => 205,
                'service_id' => 10,
                'base_price' => 145075.0,
                'admin_fee' => 0.0,
                'code' => 'BO149',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:19:53',
                'updated_at' => '2018-08-25 07:19:53'
            ],
            [
                'product_id' => 206,
                'service_id' => 10,
                'base_price' => 193600.0,
                'admin_fee' => 0.0,
                'code' => 'BO199',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:25:36',
                'updated_at' => '2018-08-25 07:25:36'
            ],
            [
                'product_id' => 207,
                'service_id' => 10,
                'base_price' => 16975.0,
                'admin_fee' => 0.0,
                'code' => 'IDN1',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:33:26',
                'updated_at' => '2019-08-09 01:54:52'
            ],
            [
                'product_id' => 208,
                'service_id' => 10,
                'base_price' => 32650.0,
                'admin_fee' => 0.0,
                'code' => 'IDN2',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:36:22',
                'updated_at' => '2019-08-09 01:57:17'
            ],
            [
                'product_id' => 209,
                'service_id' => 10,
                'base_price' => 47050.0,
                'admin_fee' => 0.0,
                'code' => 'IDN3',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:42:35',
                'updated_at' => '2019-08-02 01:52:53'
            ],
            [
                'product_id' => 210,
                'service_id' => 10,
                'base_price' => 67750.0,
                'admin_fee' => 0.0,
                'code' => 'IDN7',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:44:23',
                'updated_at' => '2019-08-05 22:49:16'
            ],
            [
                'product_id' => 211,
                'service_id' => 10,
                'base_price' => 88525.0,
                'admin_fee' => 0.0,
                'code' => 'IDN10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:46:08',
                'updated_at' => '2019-08-06 00:09:31'
            ],
            [
                'product_id' => 212,
                'service_id' => 10,
                'base_price' => 115475.0,
                'admin_fee' => 0.0,
                'code' => 'IDN15',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:47:43',
                'updated_at' => '2019-08-06 00:11:35'
            ],
            [
                'product_id' => 213,
                'service_id' => 10,
                'base_price' => 153150.0,
                'admin_fee' => 0.0,
                'code' => 'IDN99',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:50:13',
                'updated_at' => '2019-10-01 16:42:54'
            ],
            [
                'product_id' => 214,
                'service_id' => 10,
                'base_price' => 37050.0,
                'admin_fee' => 0.0,
                'code' => 'SDB1',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 07:56:09',
                'updated_at' => '2020-01-08 21:30:25'
            ],
            [
                'product_id' => 215,
                'service_id' => 10,
                'base_price' => 64000.0,
                'admin_fee' => 0.0,
                'code' => 'SDB3',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:04:31',
                'updated_at' => '2018-08-25 08:04:31'
            ],
            [
                'product_id' => 216,
                'service_id' => 10,
                'base_price' => 92600.0,
                'admin_fee' => 0.0,
                'code' => 'SDB8',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:05:21',
                'updated_at' => '2020-01-08 21:37:50'
            ],
            [
                'product_id' => 217,
                'service_id' => 10,
                'base_price' => 103100.0,
                'admin_fee' => 0.0,
                'code' => 'SDB12',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:06:09',
                'updated_at' => '2019-09-17 23:36:58'
            ],
            [
                'product_id' => 218,
                'service_id' => 10,
                'base_price' => 164550.0,
                'admin_fee' => 0.0,
                'code' => 'SDB25',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:06:57',
                'updated_at' => '2019-08-02 01:18:40'
            ],
            [
                'product_id' => 220,
                'service_id' => 10,
                'base_price' => 30750.0,
                'admin_fee' => 0.0,
                'code' => 'TGM2',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:23:12',
                'updated_at' => '2019-08-02 02:35:40'
            ],
            [
                'product_id' => 221,
                'service_id' => 10,
                'base_price' => 38700.0,
                'admin_fee' => 0.0,
                'code' => 'TGM3',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:25:48',
                'updated_at' => '2019-08-06 16:31:35'
            ],
            [
                'product_id' => 222,
                'service_id' => 10,
                'base_price' => 53900.0,
                'admin_fee' => 0.0,
                'code' => 'TGM5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:28:52',
                'updated_at' => '2018-08-25 08:28:52'
            ],
            [
                'product_id' => 223,
                'service_id' => 10,
                'base_price' => 76100.0,
                'admin_fee' => 0.0,
                'code' => 'TDC6',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:31:25',
                'updated_at' => '2019-08-06 16:35:52'
            ],
            [
                'product_id' => 224,
                'service_id' => 10,
                'base_price' => 102900.0,
                'admin_fee' => 0.0,
                'code' => 'TDC10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:33:54',
                'updated_at' => '2018-08-25 08:33:54'
            ],
            [
                'product_id' => 225,
                'service_id' => 10,
                'base_price' => 4425.0,
                'admin_fee' => 0.0,
                'code' => 'WIFI1',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:41:49',
                'updated_at' => '2018-08-25 08:41:49'
            ],
            [
                'product_id' => 226,
                'service_id' => 10,
                'base_price' => 18525.0,
                'admin_fee' => 0.0,
                'code' => 'WIFI7',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:45:06',
                'updated_at' => '2018-08-25 08:45:06'
            ],
            [
                'product_id' => 227,
                'service_id' => 10,
                'base_price' => 44525.0,
                'admin_fee' => 0.0,
                'code' => 'WIFI30',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-25 08:45:26',
                'updated_at' => '2018-08-25 08:45:26'
            ],
            [
                'product_id' => 243,
                'service_id' => 10,
                'base_price' => 0.0,
                'admin_fee' => 6250.0,
                'code' => 'ADIRA',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-26 23:56:46',
                'updated_at' => '2018-08-26 23:56:46'
            ],
            [
                'product_id' => 98,
                'service_id' => 10,
                'base_price' => 5370.0,
                'admin_fee' => 0.0,
                'code' => 'T5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-08-27 00:03:41',
                'updated_at' => '2019-08-09 23:20:30'
            ],
            [
                'product_id' => 16,
                'service_id' => 10,
                'base_price' => 97899.0,
                'admin_fee' => 0.0,
                'code' => 'S100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 12:39:49',
                'updated_at' => '2021-06-09 15:26:34'
            ],
            [
                'product_id' => 88,
                'service_id' => 10,
                'base_price' => 5900.0,
                'admin_fee' => 0.0,
                'code' => 'AX5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 12:57:50',
                'updated_at' => '2019-08-15 17:41:33'
            ],
            [
                'product_id' => 89,
                'service_id' => 10,
                'base_price' => 10735.0,
                'admin_fee' => 0.0,
                'code' => 'AX10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:00:35',
                'updated_at' => '2019-09-17 01:23:51'
            ],
            [
                'product_id' => 90,
                'service_id' => 10,
                'base_price' => 24825.0,
                'admin_fee' => 0.0,
                'code' => 'AX25',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:02:42',
                'updated_at' => '2019-09-17 01:24:37'
            ],
            [
                'product_id' => 91,
                'service_id' => 10,
                'base_price' => 49575.0,
                'admin_fee' => 0.0,
                'code' => 'AX50',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:04:19',
                'updated_at' => '2019-08-09 23:58:14'
            ],
            [
                'product_id' => 92,
                'service_id' => 10,
                'base_price' => 98650.0,
                'admin_fee' => 0.0,
                'code' => 'AX100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:06:07',
                'updated_at' => '2019-08-15 17:41:53'
            ],
            [
                'product_id' => 95,
                'service_id' => 10,
                'base_price' => 24750.0,
                'admin_fee' => 0.0,
                'code' => 'I25',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:08:55',
                'updated_at' => '2019-11-06 17:31:19'
            ],
            [
                'product_id' => 96,
                'service_id' => 10,
                'base_price' => 48975.0,
                'admin_fee' => 0.0,
                'code' => 'I50',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:10:46',
                'updated_at' => '2019-08-23 23:28:36'
            ],
            [
                'product_id' => 97,
                'service_id' => 10,
                'base_price' => 96975.0,
                'admin_fee' => 0.0,
                'code' => 'I100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:11:33',
                'updated_at' => '2019-11-04 18:48:25'
            ],
            [
                'product_id' => 93,
                'service_id' => 10,
                'base_price' => 5905.0,
                'admin_fee' => 0.0,
                'code' => 'I5',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:12:53',
                'updated_at' => '2019-09-17 18:28:06'
            ],
            [
                'product_id' => 94,
                'service_id' => 10,
                'base_price' => 10905.0,
                'admin_fee' => 0.0,
                'code' => 'I10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:14:06',
                'updated_at' => '2019-09-17 01:25:58'
            ],
            [
                'product_id' => 99,
                'service_id' => 10,
                'base_price' => 10266.0,
                'admin_fee' => 0.0,
                'code' => 'T10',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:21:21',
                'updated_at' => '2019-09-24 22:46:12'
            ],
            [
                'product_id' => 100,
                'service_id' => 10,
                'base_price' => 24596.0,
                'admin_fee' => 0.0,
                'code' => 'T25',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:23:27',
                'updated_at' => '2019-09-17 01:38:38'
            ],
            [
                'product_id' => 102,
                'service_id' => 10,
                'base_price' => 97975.0,
                'admin_fee' => 0.0,
                'code' => 'T100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:25:00',
                'updated_at' => '2019-08-13 20:14:29'
            ],
            [
                'product_id' => 101,
                'service_id' => 10,
                'base_price' => 48975.0,
                'admin_fee' => 0.0,
                'code' => 'T50',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:25:33',
                'updated_at' => '2019-09-17 01:39:31'
            ],
            [
                'product_id' => 107,
                'service_id' => 10,
                'base_price' => 98900.0,
                'admin_fee' => 0.0,
                'code' => 'SM100',
                'meta' => null,
                'status' => 1,
                'created_at' => '2018-12-22 13:30:26',
                'updated_at' => '2019-02-26 23:11:24'
            ],
            [
                'product_id' => 304,
                'service_id' => 10,
                'base_price' => 82000.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 01:11:36',
                'updated_at' => '2021-04-20 16:56:32'
            ],
            [
                'product_id' => 305,
                'service_id' => 10,
                'base_price' => 113325.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 01:14:53',
                'updated_at' => '2021-02-16 19:14:01'
            ],
            [
                'product_id' => 306,
                'service_id' => 10,
                'base_price' => 159850.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 01:22:07',
                'updated_at' => '2021-02-16 19:14:53'
            ],
            [
                'product_id' => 307,
                'service_id' => 10,
                'base_price' => 212950.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 01:24:51',
                'updated_at' => '2021-02-16 19:16:10'
            ],
            [
                'product_id' => 360,
                'service_id' => 10,
                'base_price' => 27450.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 16:57:14',
                'updated_at' => '2019-08-06 16:57:14'
            ],
            [
                'product_id' => 361,
                'service_id' => 10,
                'base_price' => 36775.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 16:58:31',
                'updated_at' => '2019-08-06 16:58:31'
            ],
            [
                'product_id' => 362,
                'service_id' => 10,
                'base_price' => 54900.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 17:00:13',
                'updated_at' => '2019-08-06 17:00:13'
            ],
            [
                'product_id' => 363,
                'service_id' => 10,
                'base_price' => 76650.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-06 17:04:47',
                'updated_at' => '2019-08-06 17:04:47'
            ],
            [
                'product_id' => 311,
                'service_id' => 10,
                'base_price' => 294975.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-07 23:57:43',
                'updated_at' => '2019-08-13 23:55:31'
            ],
            [
                'product_id' => 313,
                'service_id' => 10,
                'base_price' => 197975.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-08 00:19:59',
                'updated_at' => '2019-08-08 00:19:59'
            ],
            [
                'product_id' => 103,
                'service_id' => 10,
                'base_price' => 5075.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-09 23:36:55',
                'updated_at' => '2019-08-09 23:36:55'
            ],
            [
                'product_id' => 104,
                'service_id' => 10,
                'base_price' => 10025.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-09 23:40:35',
                'updated_at' => '2019-08-09 23:40:35'
            ],
            [
                'product_id' => 105,
                'service_id' => 10,
                'base_price' => 24725.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-09 23:42:05',
                'updated_at' => '2019-09-17 01:28:12'
            ],
            [
                'product_id' => 106,
                'service_id' => 10,
                'base_price' => 48975.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-09 23:44:06',
                'updated_at' => '2019-09-17 01:28:31'
            ],
            [
                'product_id' => 316,
                'service_id' => 10,
                'base_price' => 198900.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-09 23:46:51',
                'updated_at' => '2019-08-09 23:46:51'
            ],
            [
                'product_id' => 317,
                'service_id' => 10,
                'base_price' => 298900.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-09 23:49:02',
                'updated_at' => '2019-08-09 23:49:02'
            ],
            [
                'product_id' => 383,
                'service_id' => 10,
                'base_price' => 147550.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-12 19:23:46',
                'updated_at' => '2019-09-17 18:02:10'
            ],
            [
                'product_id' => 272,
                'service_id' => 10,
                'base_price' => 150625.0,
                'admin_fee' => 0.0,
                'code' => 'GJD150H',
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-14 18:40:47',
                'updated_at' => '2019-08-14 18:40:47'
            ],
            [
                'product_id' => 273,
                'service_id' => 5,
                'base_price' => 100850.0,
                'admin_fee' => 0.0,
                'code' => 'GJD100H',
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-14 18:41:50',
                'updated_at' => '2019-08-14 18:41:50'
            ],
            [
                'product_id' => 270,
                'service_id' => 5,
                'base_price' => 25975.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-14 18:42:15',
                'updated_at' => '2019-08-14 18:42:15'
            ],
            [
                'product_id' => 271,
                'service_id' => 5,
                'base_price' => 200850.0,
                'admin_fee' => 0.0,
                'code' => 'GJD200H',
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-14 18:43:05',
                'updated_at' => '2019-08-14 18:43:05'
            ],
            [
                'product_id' => 274,
                'service_id' => 5,
                'base_price' => 75850.0,
                'admin_fee' => 0.0,
                'code' => null,
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-14 18:44:58',
                'updated_at' => '2019-08-14 18:44:58'
            ],
            [
                'product_id' => 275,
                'service_id' => 5,
                'base_price' => 50850.0,
                'admin_fee' => 0.0,
                'code' => 'GJD50H',
                'meta' => null,
                'status' => 1,
                'created_at' => '2019-08-14 18:46:18',
                'updated_at' => '2019-08-14 18:46:18'
            ]
                
        ]);
    }
}
