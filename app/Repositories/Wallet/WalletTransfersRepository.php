<?php

namespace App\Repositories\Wallet;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Wallet\WalletTransfers;
use App\Models\Wallet\Wallets;
use App\Models\Wallet\WalletLimits;
use App\Models\Settings\SiteParams;
use App\Models\Wallet\WalletLabels;
use App\Models\Accounts\Users;
use App\Repositories\Wallet\WalletsTransactionsRepository;
use App\Repositories\Wallet\WalletsRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WalletTransfersRepository
{

    private $walletTransfert, $wallet, $walletTransRepo, $walletsRepository;

	function __construct(
        WalletTransfers $walletTransfert, 
        Wallets $wallet, 
        WalletLimits $walletLimit,
        WalletsTransactionsRepository $walletTransRepo,
        SiteParams $siteParams,
        WalletLabels $walletLabels,
        WalletsRepository $walletsRepository
    )
	{
		$this->walletTransferTransfer = $walletTransfert;
        $this->wallet = $wallet;
        $this->walletLimit = $walletLimit;
        $this->walletTransRepo = $walletTransRepo;
        $this->siteParams = $siteParams;
        $this->walletLabels = $walletLabels;
        $this->walletsRepository = $walletsRepository;
	}

    public function getWalletByUser($user, $currency='IDR', $type=1)
    {        
        $data = $this->wallet//->where('user_id',$user)
                ->where('currency', $currency)
                ->where('type', $type);
                $data->when(!empty($user), function ($q) use ($user) {
                    $f_user = Users::Where('id', $user)
                                    ->orWhere('username', $user)
                                    ->orWhere('nickname', $user)
                                    ->orWhere('phone', $user)
                                    ->orWhere('email', $user)
                                    ->first();
                    $q->where('user_id', '=', $f_user->id);
                });                            

        return $data->first();
    }

    public function updateBalanceWallet($id, $amount)
    {        
        $data = $this->wallet->where('id',$id)
                            ->update(array(
                                'balance' => DB::raw( 'balance + '.$amount )
                            ));

        return $data;
    }

    public function checkLockTransfer(Request $request)
    {        
        //wallet
        $user = Auth::id();
        $wallet = $this->getWalletByUser($user)->id;
        
        $data = $this->wallet
                    ->where('id',$wallet)
                    ->where('lock_tf',1)
                    ->get()->count();

        return $data;
    }

    // TODO: delete
    public function checkBalance()
    {        
        //wallet
        $user = Auth::id();
        $wallet = $this->getWalletByUser($user)->id;
        
        $data = $this->wallet
                    ->where('id',$wallet)
                    ->first();

        return ($data->balance - $data->hold);
    }

    public function checkLockOut(Request $request)
    {        
        //wallet
        $user = Auth::id();
        $wallet = $this->getWalletByUser($user)->id;
        
        $data = $this->wallet
                    ->where('id',$wallet)
                    ->where('lock_out',1)
                    ->get()->count();

        return $data;
    }

    public function checkLockIn($user)
    {        
        //wallet
        $wallet = $this->getWalletByUser($user)->id;
        
        $data = $this->wallet
                    ->where('id',$wallet)
                    ->where('lock_in',1)
                    ->get()->count();

        return $data;
    }

    public function getLimitTransfer(Request $request)
    {        
        //wallet
        $user = Auth::id();
        $wallet = $this->getWalletByUser($user)->id;
        
        $data = $this->walletLimit
                    ->select('transfer_daily')
                    ->where('wallet',$wallet)
                    ->first();

        return $data;
    }

    public function checkLimitTransferDaily(Request $request)
    {        
        //wallet
        $user = Auth::id();
        $wallet = $this->getWalletByUser($user)->id;

        $filter = array(
            'wallet' => $wallet,
            'transaction_type' => 'Transfer',
            'note' => 'Out',
            'created' => Carbon::now()->format('Y-m-d')
        );
        
        $data = $this->walletTransRepo->getList($filter)->sum('amount');

        return $data;
    }

    public function checkMinTransfer()
    {                
        $data = $this->siteParams
                    ->select('value')
                    ->where('name','transfer_min')
                    ->first();

        return $data;
    }

    public function checkLabel(Request $request)
    {                
        $data = $this->walletLabels
                    ->where('id',$request->input('label'))
                    ->where('spending',1)
                    ->get()
                    ->count();

        return $data;
    }

    public function store(Request $request, $user_to){

        //wallet from
        $user = $request->user();
        $wallet_from = $this->getWalletByUser($user->id)->id;
        
        //wallet to
        $wallet_to = $this->getWalletByUser($user_to)->id;
        
        $amount = $request->input('amount');

        DB::beginTransaction();
        try {
            $data = WalletTransfers::create([
                'from' => $user->id,
                'to' => $wallet_to,
                'amount' => $amount,
                'label' => $request->input('label'),
                'message' => $request->input('message'),
                'description_from' => $request->input('description_from'),
                'description_to' => $request->input('description_to'),
                'background' => $request->input('background'),
                'reff' => $request->input('reff'),
            ]);    
            
            $data_trans = [];
            $data_trans  = array(
                array(
                    'wallet_id' => $wallet_from,
                    'reff_id' => $data->id,
                    'amount' => $amount,
                    'transaction_type' => 'Transfer',
                    'status' => 3,
                    'note' => 'Out',
                    'label_id' => $request->input('label'),
                    'balance_before'=> $this->walletsRepository->checkBalance(),
                    'location' => $user->token()->location,
                    'created_at' => now(),
                    'updated_at' => now()
                ),
                array(
                    'wallet_id' => $wallet_to,
                    'reff_id' => $data->id,
                    'amount' => $amount,
                    'transaction_type' => 'Transfer',
                    'status' => 3,
                    'note' => 'In',
                    'label_id' => 2,
                    'balance_before'=> $this->walletsRepository->checkBalance($user_to),
                    'location' => $user->token()->location,
                    'created_at' => now(),
                    'updated_at' => now()
                )
            );
    
            $wallet_transaction = $this->walletTransRepo->storeMultiple($data_trans);
    
            $balance_from = $this->updateBalanceWallet($wallet_from, $amount * -1);
    
            $balance_to = $this->updateBalanceWallet($wallet_to, $amount);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return false;
        }
        return $data;
    }

    

}
