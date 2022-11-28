<?php

namespace App\Repositories\Wallet;

use App\Models\Wallet\WalletLimits;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet\WalletTopup;
use App\Models\Wallet\WalletTransactions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WalletTopupRepository
{
  private $walletTopup;
  private $walletLimits;
  private $walletsTransRepo;

	function __construct(WalletTopup $walletTopup, WalletLimits $walletLimits, WalletsTransactionsRepository $walletsTransRepo)
	{
		$this->walletTopup = $walletTopup;
    $this->walletLimits = $walletLimits;
    $this->walletsTransRepo = $walletsTransRepo;
	}

  public function checkCurrentActiveTopup ($userId){
    $today = date('Y-m-d H:i:s');
    $activeTopup = $this->walletTopup
                        ->where('user_id', $userId)
                        ->where('status', 2)
                        ->where('expires', '>', $today)
                        ->first();
    return $activeTopup ? $activeTopup->toArray() : $activeTopup;
  }

  public function addWalletTopup ($topupInfo){
    $topup = $this->walletTopup->insertGetId($topupInfo);
    return $topup;
  }

  public function getLimitWallet($walletId, $field_name)
    {        
        //wallet
        $data = $this->walletLimits
                    ->select($field_name)
                    ->where('wallet',$walletId)
                    ->first();

        return $data;
    }

    public function checkLimitTopupDaily($walletId)
    {        
        //wallet
        $filter = array(
            'wallet' => $walletId,
            'transaction_type' => 'Topup',
            'status' => '3',
            'created' => Carbon::now()->format('Y-m-d')
        );

        $data = $this->walletsTransRepo->getList($filter)->sum('amount');

        return $data;
    }

    public function updateTableByField($walletTopupId, $data){
      $this->walletTopup->where('id', $walletTopupId)->update($data);
    }
}
