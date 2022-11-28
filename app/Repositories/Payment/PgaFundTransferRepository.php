<?php

namespace App\Repositories\Payment;

use App\Models\Payment\PgaFundTransfers;
use App\Repositories\Accounts\UsersRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PgaFundTransferRepository
{
    private $pgaFundTransfers;

    private $usersRepo;

    public function __construct(
        PgaFundTransfers $pgaFundTransfers,
        UsersRepository $usersRepo
    ) {
        $this->PgaFundTransfers = $pgaFundTransfers;
        $this->usersRepo = $usersRepo;
    }

    public function get($where)
    {
        $data = $this->PgaFundTransfers->where($where)->get();

        return $data;
    }

    public function store($data)
    {
        $merchant_code = env('PGA_MERCHANT_CODE');
        $merchant_secret = env('PGA_MERCHANT_SECRET');
        $url = env('PGA_URL').'/api/disbursement/send';

        $signature_string = $merchant_code.':'.$data['unique_id'].':'.$data['bank_code'].':'.$data['bank_account_number'].':'.$data['amount'];
        $valiadation_signature = hash_hmac('sha256', $signature_string, $merchant_secret);

        $user = $this->usersRepo->getUserAllByField('id', Auth::id());

        $data_post = [
            'merchant_code' => $merchant_code,
            'unique_id' => $data['unique_id'],
            'description' => $data['description'],
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone_code.$user->phone,
            'bank_code' => $data['bank_code'],
            'bank_account_name' => $data['bank_account_name'],
            'bank_account_number' => $data['bank_account_number'],
            'amount' => $data['amount'],
            'execute_at' => '0',
            'signature' => $valiadation_signature,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $data_post);

        $status = $response->json();

        if ($status['status'] == '000') {
            $data = [
                'transaction_id' => $data['transaction_id'],
                'action' => $data['description'],
                'reference_no' => $status['data']['reference_no'],
                'unique_id' => $status['data']['unique_id'],
                'amount' => $status['data']['amount'],
                'fee' => $status['data']['fee'],
                'merchant_surcharge_rate' => $status['data']['merchant_surcharge_rate'],
                'charge_to' => $status['data']['charge_to'],
                'payout_amount' => $status['data']['payout_amount'],
                'disbursement_status' => $status['data']['disbursement_status'],
                'disbursement_description' => $status['data']['disbursement_description'],
                'bank_code' => $status['data']['bank']['code'],
                'bank_name' => $status['data']['bank']['name'],
                'bank_account_number' => $status['data']['bank']['account_number'],
                'bank_account_name' => $status['data']['bank']['account_name'],
                'status' => $status['status'],
                'created_at' => $status['data']['created_at'],
            ];

            return $this->insert($data);
        } else {
            $data = [
                'unique_id' => $data['unique_id'],
                'transaction_id' => $data['transaction_id'],
                'action' => $data['description'],
                'status' => $status['status'],
                'error_message' => $status['error_message'],
            ];

            return $this->insert($data);
        }

        return $data;
    }

    private function insert($data)
    {
        $data = PgaFundTransfers::create($data);

        return $data;
    }
}
