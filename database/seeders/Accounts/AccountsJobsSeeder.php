<?php

namespace Database\Seeders\Accounts;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AccountsJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts.jobs')->insert([
            'id' => 1,
            'name' => '{"id": "Pegawai Negeri Sipil", "en": "Government employees"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 2,
            'name' => '{"id": "Pegawai BI/BUMN/BUMD", "en": "BI/BUMN/BUMD employees"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 3,
            'name' => '{"id": "TNI / Polri", "en": "Military / Police"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 4,
            'name' => '{"id": "Notaris", "en": "Notary Public"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 5,
            'name' => '{"id": "Pengacara", "en": "Lawyer"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 6,
            'name' => '{"id": "Hakim / Jaksa", "en": "Judge / Prosecutor"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 7,
            'name' => '{"id": "Pedagang", "en": "Merchant"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 8,
            'name' => '{"id": "Anggota Organisasi keagamaan", "en": "Members of religious organizations"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 9,
            'name' => '{"id": "Pejabat Lembaga Eksekutif", "en": "Official Executive Institution"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 10,
            'name' => '{"id": "Pejabat Lembaga Legislatif", "en": "Official Executive Institution"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 11,
            'name' => '{"id": "Pejabat Lembaga Yudikatif", "en": "Official of the Legislative Institution"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 12,
            'name' => '{"id": "Pekerja Sektor Informal", "en": "Informal Sector Workers"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 13,
            'name' => '{"id": "Pegawai Swasta Penyedia Jasa Keuangan", "en": "Private Service Provider of Financial Services"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 14,
            'name' => '{"id": "TKI", "en": "Forgein Indonesian workers"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 15,
            'name' => '{"id": "Pelajar & Mahasiswa", "en": "Students"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 16,
            'name' => '{"id": "Pegawai Swasta", "en": "Private employees"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 17,
            'name' => '{"id": "Pekerja Kreatif", "en": "Creative Workers"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 18,
            'name' => '{"id": "Pengurus / Pegawai Yayasan", "en": "Foundation Management / Employees"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 19,
            'name' => '{"id": "Wartawan", "en": "Journalist"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 20,
            'name' => '{"id": "Dokter / Perawat", "en": "Doctor / nurse"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 21,
            'name' => '{"id": "Petani", "en": "Farmer"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 22,
            'name' => '{"id": "Nelayan", "en": "Fisherman"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 23,
            'name' => '{"id": "Mengurus Rumah Tangga", "en": "Housekeeper"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 24,
            'name' => '{"id": "Pengrajin", "en": "Craftsmen"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 25,
            'name' => '{"id": "Pengurus Partai Politik", "en": "Management of Political Parties"}',
            'type' => 'Medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 26,
            'name' => '{"id": "Pengusaha / Wiraswasta", "en": "Entrepreneur"}',
            'type' => 'High',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('accounts.jobs')->insert([
            'id' => 27,
            'name' => '{"id": "Belum / Tidak Bekerja", "en": "Jobsless"}',
            'type' => 'Low',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    }
}
