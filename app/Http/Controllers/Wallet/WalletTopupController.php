<?php

namespace App\Http\Controllers\Wallet;

use App\Events\Wallet\WalletTopup;
use App\Http\Controllers\Controller;
use App\Models\Payment\PgaBills;
use App\Repositories\Accounts\BanksRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\WalletTransactionNotifRepository;
use App\Repositories\Wallet\WalletsRepository;
use App\Repositories\Wallet\WalletsTransactionsRepository;
use App\Repositories\Wallet\WalletTopupRepository;
use App\Repositories\Payment\PgaBillsRepository;
use App\Repositories\RedisRepository;
use App\Repositories\Wallet\TopupInstructionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Bridge\UserRepository;
use Illuminate\Support\Facades\DB;

class WalletTopupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $walletTopupRepository;
    private $banksRepository;
    private $walletsRepository;
    private $usersRepository;
    private $walletsTransactionsRepository;
    private $pgaBillsRepository;
    private $redisRepository;
    private $notificationRepository;

    public function __construct
    (
      WalletTopupRepository $walletTopupRepository,
      BanksRepository $banksRepository,
      WalletsRepository $walletsRepository,
      UsersRepository $usersRepository,
      WalletsTransactionsRepository $walletsTransactionsRepository,
      PgaBillsRepository $pgaBillsRepository,
      RedisRepository $redisRepository,
      NotificationRepository $notificationRepository
    )
    {
        $this->walletTopupRepository = $walletTopupRepository;
        $this->banksRepository = $banksRepository;
        $this->walletsRepository = $walletsRepository;
        $this->usersRepository = $usersRepository;
        $this->walletsTransactionsRepository = $walletsTransactionsRepository;
        $this->pgaBillsRepository = $pgaBillsRepository;
        $this->redisRepository = $redisRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function topupWallet(Request $request){
      $response = init_transaction_data($request, null);
      $user = $request->user();
      $userWallet = $this->walletsRepository->getWalletByUser($user->id);
      $userToken = $this->usersRepository->getAccessTokens($user);
      
      // validate the data
      $validate = Validator::make($request->all(), [
        'label' => 'required',
        'amount' => 'required|numeric|gt:0',
        'currency' => 'required',
        'bank_to' => 'required'
      ]);

      if ($validate->fails()) {
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;
        $response['response']['message'] = 'error';
        $response['response']['data'] = $validate->errors();

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      // check if there is active top up (from db)
      $activeTopup = $this->walletTopupRepository->checkCurrentActiveTopup($user->id);

      if ($activeTopup) {
        $response['response']['message'] = trans('messages.unfinished-topup');
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      // check if the bank destination exist (from body & db)
      $bank_to = $this->banksRepository->getBankById($request->bank_to);

      if (!$bank_to) {
        $response['response']['message'] = trans('messages.bank-invalid');
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      // check label
      $checkLabel = $this->walletsRepository->checkLabel($request->label, 0);
      if (!$checkLabel) {
        $response['response']['message'] = trans('messages.label-invalid');
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      // check topup limit
      $topupLimit = $this->walletTopupRepository->getLimitWallet($userWallet->id, 'topup_daily')->topup_daily;
      $sumOfDailyTopupToday = $this->walletTopupRepository->checkLimitTopupDaily($userWallet->id);
      $sumOfTopupAfterCurrentTopup = $request->amount + $sumOfDailyTopupToday;

      if ($sumOfTopupAfterCurrentTopup > $topupLimit ||  $request->amount > $topupLimit) {
        $response['response']['message'] = trans('messages.topup-limit-exceeded');
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      // check wallet balance
      $walletBalance = $this->walletsRepository->checkBalance();
      $sumAfterTopup = $walletBalance + $request->amount;
      $walletLimit = $this->walletTopupRepository->getLimitWallet($userWallet->id, 'max_balance')->max_balance;
      if ($sumAfterTopup > $walletLimit) {
        $response['response']['message'] = trans('messages.balance-limit-exceeded');
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      DB::beginTransaction();
        try {
            // create topup
            $data = [
              'amount' => $request->amount,
              'user_id' => $user->id,
              'wallet_id' => $userWallet->id,
              'label_id' => $request->label,
              'bank_to_id' => $bank_to['id'],
              'bank_name' => $bank_to['name'],
              'bank_code' => $bank_to['code'],
              'bank_account_name' => $bank_to['account_name'],
              'bank_account_number' => $bank_to['account_number'],
              'payment_method_code' => $bank_to['payment_method_code'],
              'expires' => date("Y-m-d H:i:s", strtotime('+2 hours')),
              'created_at' => now(),
              'updated_at' => now()
            ];

            $walletTopUpId = $this->walletTopupRepository->addWalletTopup($data);

            // create transaction
            $transactionData = [
              'wallet_id' => $userWallet->id,
              'reff_id' => $walletTopUpId,
              'amount' => $request->amount,
              'transaction_type' => 'Topup',
              'status' => 2,
              'note' => 'In',
              'label_id' => $request->label,
              'location' => $userToken->location,
              'balance_before' => $this->walletsRepository->checkBalance(),
              'created_at' => now(),
              'updated_at' => now()
            ];

            $walletTransaction = $this->walletsTransactionsRepository->store($transactionData);

            //request PGA (Payment Gateway)
            $pgaData = [
              'invoice_no' => 'TU-'.date("Y-n-d")."-".$walletTransaction,
              'description' => 'Topup',
              'amount' => $request->amount,
              'bank_to' => $bank_to['id'],
              'transaction_id' => $walletTransaction,
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            //return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        // Request PGA
        if($walletTransaction != null || !empty($walletTransaction)){
          $pgaBill = $this->pgaBillsRepository->store($pgaData); 

          if ($pgaBill->status == '000') {
            $status['fee'] = $pgaBill->surcharge;
            $status['total'] = $pgaBill->amount;

            $pgaBill->status = 2;

            $this->walletTopupRepository->updateTableByField($walletTopUpId, $status);

            $response['response']['message'] = trans('messages.topup-created');
            $response['response']['data'] = $pgaBill;
          } else {
            // if there is error in pga request
            $status = [
              'status' => '1'
            ];
            $this->walletsTransactionsRepository->update($walletTransaction, $status);
            $this->walletTopupRepository->updateTableByField($walletTopUpId, $status);

            $pgaBill->status = 1;
            
            $response['response']['message'] = $pgaBill->error_message;
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
          }
        }

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function cancelTopup(Request $request, $id){
      $response = init_transaction_data($request);
      $user = $request->user();
      $userWallet = $this->walletsRepository->getWalletByUser($user->id);

      // check the transaction
      $filter = [
        'id' => $id,
        'wallet_id' => $userWallet->id,
        'transaction_type' => 'Topup',
        'status' => 2
      ];

      $transactionInfo = $this->walletsTransactionsRepository->getTransaction($filter);
      
      if (!$transactionInfo) {
        $response['response']['message'] = trans('messages.topup-cancel-failed');
        $response['response']['success'] = false;
        $response['response']['response_code'] = 422;

        return Response($response['response'])->header('Content-Type', 'application/json');
      }

      // cancel the wallet topup and transaction status based on id
      DB::beginTransaction();
      try {
        $this->walletsTransactionsRepository->update($id, ['status'=> 0]);
        $this->walletTopupRepository->updateTableByField($transactionInfo->reff_id, ['status'=> 0]);
        DB::commit();
      } catch (\Exception $e) {
        DB::rollBack();
        // dd($e->getMessage());
        return $this->errorResponse($e->getMessage(), $e->getCode());
      }

      $response['response']['message'] = trans('messages.topup-cancelled');

      return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function getTopupTransaction (Request $request, $id) {
      $response = init_transaction_data($request);

      // check to pga_bills
      $filter = [
        'transaction_id' => $id,
        'action' => 'Topup',
      ];

      $response['response']['data'] = null;
      $response['response']['message'] = "Data no record";
      $data = $this->pgaBillsRepository->get($filter);
      if(isset($data)){
        
        // not found
        if (!$data->count() || $data->status != 000 ) {
          $response['response']['message'] = trans('messages.transaction-not-found');
          $response['response']['success'] = false;
          $response['response']['response_code'] = 422;

          return Response($response['response'])->header('Content-Type', 'application/json');
        }
        
        if ($data->paid_status == 1) {
          $data->net_amount = (double)$data->net_amount;
          $data->surcharge = (double)$data->surcharge;
          $data->amount = (double)$data->amount;
          $response['response']['data'] = $data;
          $response['response']['message'] = trans('messages.get-topup-transaction');

          return Response($response['response'])->header('Content-Type', 'application/json'); 
        }

        // check with pga
        $pgaData = $this->pgaBillsRepository->getBillPga($data->toArray());
        // check the response
        if ($pgaData['status'] != '000') {
          if (array_key_exists('failed_update_data', $pgaData)) {
            $response['response']['message'] = $pgaData['failed_update_data'];
          } else {
            $response['response']['message'] = $pgaData['error_message'];
          }
          $response['response']['success'] = false;
          $response['response']['response_code'] = 422;

          return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $responseData = $pgaData['data'];
        // check to DB
        $responseData['status'] = 3;
        $responseData['transaction_id'] = $id;
        $responseData['id'] = $data->id;
        $responseData['net_amount'] = (double)$responseData['net_amount'];
        $responseData['surcharge'] = (double)$responseData['surcharge'];
        $responseData['amount'] = (double)$responseData['amount'];

        $response['response']['data'] = $responseData;
        // $response['response']['data'] = $pgaData['data'];
        $response['response']['message'] = trans('messages.get-topup-transaction');
      }
      
      return $response['response'];

      
    }

    public function getTopupInstruction (Request $request, $bank_code, $lang){
      $response = init_transaction_data($request);

      // $dataFromRedis = $this->redisRepository->get('topupInstruction'.$method);

      // if (json_decode($dataFromRedis, true) || $dataFromRedis != false) {
      //   $response['response']['data'] = json_decode($dataFromRedis, true);
      //   $response['response']['message'] = trans('messages.instruction-found');

      //   return Response($response['response'])->header('Content-Type', 'application/json');
      // }

      $data = $this->walletsRepository->getInstruction($bank_code, 'Topup', $lang);

      if (!$data) {
        $response['response']['message'] = trans('messages.instruction-not-found');
        $response['response']['response_code'] = 422;
        $response['response']['success'] = false;
      } else {
        $response['response']['data'] = $data;
        $response['response']['message'] = trans('messages.instruction-found');
      }

      // $setRedis = $this->redisRepository->set('topupInstruction'.$method, json_encode($data));

      return Response($response['response'])->header('Content-Type', 'application/json'); 
    }

    public function topupCallback (Request $request){
      $response = init_transaction_data($request);
      $info = $request->all();
      $invoice_number = $info['invoice_no'];
      $pgaBills = $this->pgaBillsRepository->get(['invoice_no' => $invoice_number]);
      $walletTransaction = $this->walletsTransactionsRepository->getTransaction(['id' => $pgaBills->transaction_id]);
      $user_id = $this->usersRepository->getUserByField('phone', substr($pgaBills->customer_phone, 2));

      if (!isset($pgaBills)) {
        $response['response']['message'] = 'Bills not found';
        $response['response']['response_code'] = 200;
        $response['response']['success'] = true;
        return Response($response['response'])->header('Content-Type', 'application/json'); 
      }

      if ($pgaBills->status != '000') {
        $response['response']['message'] = 'Pga bills error';
        $response['response']['response_code'] = 200;
        $response['response']['success'] = true;

        return Response($response['response'])->header('Content-Type', 'application/json'); 
      }

      if ($pgaBills->paid_status == 1) {
        $response['response']['message'] = 'bills have been paid';
        $response['response']['response_code'] = 200;
        $response['response']['success'] = true;

        return Response($response['response'])->header('Content-Type', 'application/json'); 
      }

      if ($info['paid_status'] == 1) {
        // change the status
        DB::beginTransaction();
        try {
          // change the pgabills
          $pgaInfo = [
            'paid_status'       => $info['paid_status'],
            'paid_description'  => $info['paid_description'],
            'description'       => $info['description'],
            'paid_at'           => $info['paid_at'],
          ];

          $this->pgaBillsRepository->update($pgaBills->transaction_id, $pgaInfo);

          // add balance
          $this->walletsRepository->addBalance($walletTransaction->wallet_id, $walletTransaction->amount);

          $info = [
            'status' => 3
          ];
          // edit status in wallet topup
          $this->walletTopupRepository->updateTableByField($walletTransaction->reff_id, $info);

          // edit status in wallet transaction
          $this->walletsTransactionsRepository->update($walletTransaction->id, $info);

          DB::commit();
        } catch (\Exception $e) {
          DB::rollBack();
          dd($e->getMessage());
        }


        // send feedback to front end
        $info = [
          'title' => env('APP_NAME'),
          'body' => trans('messages.topup-success', ['amount' => 'Rp.'.number_format($pgaBills->net_amount)]),
          'user_id' => $user_id->id
        ];
        
        $this->notificationRepository->sendWebNotification($info);

        $response['response']['message'] = 'Topup success';
        $response['response']['response_code'] = 200;
        $response['response']['success'] = true;
        $response['response']['data'] = $info;

        return Response($response['response'])->header('Content-Type', 'application/json'); 
      }
    }

}
