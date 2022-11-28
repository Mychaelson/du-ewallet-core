<?php

namespace App\Repositories\Wallet;

use App\Models\Wallet\WalletLimits;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WalletLimitRepository
{

    private $walletLimits;
    private $walletsTransRepo;

    function __construct(WalletLimits $walletLimits, WalletsTransactionsRepository $walletsTransRepo)
    {
      $this->walletLimits = $walletLimits;
      $this->walletsTransRepo = $walletsTransRepo;
    }

    public function addWalletLimit ($data){
      $response = $this->walletLimits->insertGetId($data);
      return $response;
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

    public function checkLimitTransferDaily($walletId)
    {        
        //wallet
        $filter = array(
            'wallet' => $walletId,
            'transaction_type' => 'Transfer',
            'status' => '3',
            'created' => Carbon::now()->format('Y-m-d')
        );

        $data = $this->walletsTransRepo->getList($filter)->sum('amount');

        return $data;
    }
}
