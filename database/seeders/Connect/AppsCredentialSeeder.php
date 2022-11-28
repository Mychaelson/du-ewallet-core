<?php

namespace Database\Seeders\Connect;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppsCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('connect.apps_credentials')->insert([
            'id' => 1,
            'user_id' => 12875,
            'merchant_id' => 4,
            'client_secret' => 'bUZXMjAzM0xob0w1UitIQnM1TG5VM3VsNEx1eGpQeHROd0xZSXhaTEp4eDRFcE9FTHRYdU9YWTcyOXlOeDNWbA==',
            'client_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjczZGE1Y2Q3YTUyNWU0ZjlhMmJjNGRhZjdjYTY0Mjg4NjgyZWVkNGIxMzNlZjQ1ZTJjNjc4YTFiNzliZDYxM2E3NGM5YzNmMzQ3N2U3MGVkIn0.eyJhdWQiOiIxIiwianRpIjoiNzNkYTVjZDdhNTI1ZTRmOWEyYmM0ZGFmN2NhNjQyODg2ODJlZWQ0YjEzM2VmNDVlMmM2NzhhMWI3OWJkNjEzYTc0YzljM2YzNDc3ZTcwZWQiLCJpYXQiOjE1NzM1NDM1NDUsIm5iZiI6MTU3MzU0MzU0NSwiZXhwIjoxNTczODAyNzQ1LCJzdWIiOiIxMjg3NSIsInNjb3BlcyI6W119.Ire0KMgOAE5TfG1SsTUzANw1JLgAAdeO9eGx3hn216qExq-u9MWwNoRnna_D_II72zMiUKbDWpOFCNqVujwkI9SK2yFn3kGv9SKtHrLPwDbXptwoDKauas_CsQ6YDYKSftmkSNvHg9mjGU0aMnw7P0lDcdMz6mCXO_Siwjqi0BrUWv77xN8lgoF2lrr9ItZOTCmlFjh-jR49F0KVDyd89mQ1qCrrp9LJiMKowVtYoU6DWZVwsubemN9GW7j7Ax6JADDY5gC0RBALuLQBkljSDDiGrimqBEfW6AxNeJXk7eo8HMFEDnFXzhTXKwJNDUMj_B1WWdQ8NfT2Tq-ZyT9R5rL1Koz8dCbxQ5BO7ojeIFCKZ6s0G-T6BFY7h-nCWHKov7YvPn974VLfSxpEPY5erPeEfCTid3TPE03Unjh_EbPDeVmrIJ7AOVT_hkpWbNdGE4cXFCKGip8POuv_ADcATkspBiUBYaEJZAJDtJQTZeoGGMGtZMAQ1wbGqtHx7Ee8GfLT22x8rzLnKjJKC7ZcKQLB7Qu1CqlM61f7bxMaBW40NdIYAPuzJCpIJOelfIXHdaYnv6lKattd-xykpKbun466qVr_AoPT36Tzh-bbeZZIDKk-JbmmFkL532uxIKLkM62UW3waB1Ph3G7DM2wkUzKsh0w7SONFlQv5NAtrB20',
            'refresh_token' => 'def5020026af3ec09f29ddca87b4ffee2bace7cf5fed5735548bfc7d89bd16d09a7a45c6293a4a2dff60ae166e2d49fa93a6df67c45428f70a4f618133c399b099ca160dfc909db0c6be110d12f9b94c7c125e3e6a60e261174d9c2d6c79ce686713fd706f774920e773bfb2b083e893c6f5449ffc6aa0dc46393bec00a667d4a6c6984e254c63a88de2c9c6c3cc76399239d410ae9aaf8e6114170eb2acf5b99fd69b6de4e9f5c3c89d0cf3ced53872449942eb554264a4c9615aac23571407b2eb454e669171ee35543143ec4b9711534e8afcd3bffe82c4531b946148c6487dba723c5e99d0672b0e8d099c2760fa2324f2e4096478238317520b95f7c8a0663bf8cbce6bf90deac680e47e7114b50652d05033bad308d1c5a3c4a5f5ac6b46b5151df5f7ffbe56347582d567ddf4a1b3a03b50fb84c956e60e2a42fd8e8400f86e02b7f23c0e9613b20d6d2d2c77f09df865d5b66881079eb50cf0be15f6727da4c058',
            'revoked' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
