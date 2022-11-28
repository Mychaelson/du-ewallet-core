<?php

namespace App\Repositories\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class PgaBankAccountRepository
{
	function __construct()
	{
		//
	}

	public function chekBankAccount ($data){
		$url = env('PGA_URL')."/api/disbursement/bank_account_inquiry";
		$merchant_code = env('PGA_MERCHANT_CODE');
		$merchant_secret = env('PGA_MERCHANT_SECRET');
		$signature_string = $merchant_code.':'.$data['bank'].':'.$data['number'];
		$hashedSignature = hash_hmac('sha256', $signature_string, $merchant_secret);

		// request to pga
		$data_post = [
			'merchant_code' => $merchant_code,
			'bank_code' => $data['bank'],
			'bank_account_number' => $data['number'],
			'signature' => $hashedSignature
		];

		$response = Http::withHeaders([
			'Content-Type' => 'application/json'
		])->post($url, $data_post);

		$response = $response->json();

		
		return $response;
	}
}