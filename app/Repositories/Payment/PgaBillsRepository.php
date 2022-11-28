<?php

namespace App\Repositories\Payment;

use App\Models\Payment\PgaBills;
use App\Models\Accounts\CompanyBankAccounts;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Accounts\UsersRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Repositories\Wallet\WalletsRepository;
use App\Repositories\Wallet\WalletsTransactionsRepository;
use App\Repositories\Wallet\WalletTopupRepository;

class PgaBillsRepository
{
	private $pgaBills, $usersRepo, $walletsTransactionsRepository, $walletTopupRepository, $walletsRepository;

	function __construct(
		PgaBills $pgaBills,
		UsersRepository $usersRepo,
		WalletsTransactionsRepository $walletsTransactionsRepository,
		WalletTopupRepository $walletTopupRepository,
		WalletsRepository $walletsRepository
	)
	{
		$this->PgaBills = $pgaBills;
		$this->usersRepo = $usersRepo;
		$this->walletsTransactionsRepository = $walletsTransactionsRepository;
		$this->walletTopupRepository = $walletTopupRepository;
		$this->walletsRepository = $walletsRepository;
	}

	public function get($where)
	{
		$data = $this->PgaBills->where($where)->first();

		return $data;
	}

	public function store($data)
	{
		$merchant_code = env('PGA_MERCHANT_CODE');
		$merchant_secret = env('PGA_MERCHANT_SECRET');
		$url = env('PGA_URL')."/api/pay/create";

		$signature_string = $merchant_code.":".$data['invoice_no'];
        $valiadation_signature = hash_hmac('sha256', $signature_string, $merchant_secret);

		$bankAccount = CompanyBankAccounts::where('bank_id',$data['bank_to'])->first();

		$user = $this->usersRepo->getUserAllByField('id', Auth::id());
		
		$data_post = array(
			'merchant_code' =>$merchant_code,
			'invoice_no' =>$data['invoice_no'],
			'description' =>$data['description'],
			'customer_name' =>$user->name,
			'customer_email' =>$user->email,
			'customer_phone' =>$user->phone_code.$user->phone,
			'expire_in' =>'10',
			'payment_method_code' =>$bankAccount->payment_method_code,
			'amount' =>$data['amount'],
			'signature' =>$valiadation_signature
		);

		$response = Http::withHeaders([
			'Content-Type' => 'application/json'
		])->post($url, $data_post);

		$status = $response->json();

		if($status['status'] == '000'){
			$data = array(
				'transaction_id'=>$data['transaction_id'],
				'action'=>$data['description'],
				'merchant_code'=>$status['data']['merchant_code'],
				'reference_no'=>$status['data']['reference_no'],
				'invoice_no'=>$status['data']['invoice_no'],
				'customer_name'=>$status['data']['customer']['name'],
				'customer_phone'=>$status['data']['customer']['phone'],
				'customer_email'=>$status['data']['customer']['email'],
				'description'=>$status['data']['description'],
				'net_amount'=>(double)$status['data']['net_amount'],
				'surcharge'=>(double)$status['data']['surcharge'],
				'surcharge_to'=>$status['data']['surcharge_to'],
				'amount'=>(double)$status['data']['amount'],
				'payment_method'=>$status['data']['payment_method'],
				'payment_method_code'=>$status['data']['payment_method_code'],
				'pay_code'=>$status['data']['pay_code'],
				'pay_qrcode'=>$status['data']['pay_qrcode'],
				'pay_checkout_url'=>$status['data']['pay_checkout_url'],
				'pay_mobile_deeplink'=>$status['data']['pay_mobile_deeplink'],
				'paid_status'=>$status['data']['paid_status'],
				'paid_description'=>$status['data']['paid_description'],
				'form_url'=>$status['form_url'],
				'status'=>$status['status'],
				'created_at'=>$status['data']['created_at']
			);
			return $this->insert($data);
		} else {
			$data = array(
				'transaction_id'=>$data['transaction_id'],
				'action'=>$data['description'],
				'status'=>$status['status'],
				'error_message' => $status['error_message']
			);
			return $this->insert($data);
		}
	}

	private function insert($data){
		$data = PgaBills::create($data);

		return $data;
	}

	public function getBillPga ($data){
		$url = env('PGA_URL')."/api/pay/check_b";
		$merchant_secret = env('PGA_MERCHANT_SECRET');
		$signature_string = $data['merchant_code'].':'.$data['reference_no'];
		$hashedSignature = hash_hmac('sha256', $signature_string, $merchant_secret);
		$transaction_id = $data['transaction_id'];

		// request to pga
		$data_post = [
			'merchant_code' => $data['merchant_code'],
			'reference_no' => $data['reference_no'],
			'signature' => $hashedSignature
		];

		$response = Http::withHeaders([
			'Content-Type' => 'application/json'
		])->post($url, $data_post);

		$response = $response->json();

		// if request to pga failed
		if ($response['status'] != '000') {
			// update the pga_bills table
			$data = array(
				'status' => $response['status'],
				'error_message' => $response['error_message']
			);

			$this->update($transaction_id, $data);
			return $response;
		}

		// check if update status needed
		$resPGA = $data['data']['paid_status'];
		if ($resPGA != $data['paid_status']) {
			// contruct data from pga local
			$dataUpdate = [
				'transaction_id' => $transaction_id,
				'data' => $response['data']
			];
			
			$updateData = $this->updateTopupstatus($dataUpdate);

			if ($updateData) {
				$response['failed_update_data'] = $updateData;
			}
		}
		
		// return something
		return $response;
	}

	private function updateTopupstatus ($data) {
		// find row on each table (table wallet transaction and wallet topup)
		$transaction_id = $data['transaction_id'];
		$transaction = $this->walletsTransactionsRepository->getTransaction(['id' => $transaction_id]);
		$walletTopup_id = $transaction->reff_id;

		DB::beginTransaction();
		try {
			// construct the data
			$update_data = [
				'paid_status' => $data['data']['paid_status'],
				'paid_description' => $data['data']['paid_description'],
				'paid_at' => $data['data']['paid_at']
			];

			// update pga table
			// return this result
			$this->update($transaction_id, $update_data);

			// update the wallet transaction and topup table
			// status wallet
			// 0 = deleted (by user), 
			// 1 = cancelled or rejected, 
			// 2 = new, 
			// 3 = done

			// status PGA
			// 0 = unpaid, 
			// 1 = paid, 
			// 3 = expired -> PGA

			// Test Dummy Data
			$resPGA = $data['data']['paid_status'];

			if ($resPGA == 1) {
				// update wallet balance
				$this->walletsRepository->addBalance($transaction['wallet_id'], $transaction->amount);

				$data = ['status' => 3];				
			} elseif ($resPGA == 3) {
				$data = ['status' => 1];
			}

			// the transaction table
			$this->walletsTransactionsRepository->update($transaction_id, $data);
			// update wallet topup status
			$this->walletTopupRepository->updateTableByField($walletTopup_id, $data);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			return $e->getMessage();
		}
	}

	public function update ($transaction_id, $data){
		$data = PgaBills::where('transaction_id', $transaction_id)->update($data);
		return $data;
	}
}