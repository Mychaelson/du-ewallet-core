<?php

namespace App\Repositories\Wallet;

use App\Models\Accounts\BankInstruction;
use App\Models\Wallet\WalletLabels;
use App\Models\Wallet\ServicePassword;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet\Wallets;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WalletsRepository
{

    private $wallet;
    private $walletLabels;
    private $bankInstruction;

	function __construct(Wallets $wallet, WalletLabels $walletLabels, BankInstruction $bankInstruction)
	{
		$this->wallet = $wallet;
        $this->walletLabels = $walletLabels;
        $this->bankInstruction = $bankInstruction;
	}
    
    public function getWallet()
    {
        $userId = Auth::id();
        $data = $this->wallet->where('user_id',$userId)->orderBy('id','DESC')->get();

        return $data;
    }

    public function getLabel()
    {
        $data = $this->walletLabels->get();

        return $data;
    }

    public function getLabelById($id)
    {
        $data = $this->walletLabels->where('id', $id)->get();

        return $data;
    }

    public function getWalletById($id)
    {
        
        $data = $this->wallet->where('id',$id)->first();

        return $data;
    }

    public function getWalletByUser($user, $currency='IDR', $type=1)
    {        
        $data = $this->wallet->where('user_id',$user)
                            ->where('currency', $currency)
                            ->where('type', $type)
                            ->first();

        return $data;
    }

    public function checkLabel($labelId, $isSpending = 1)
    {                
        $data = $this->walletLabels
                    ->where('id', $labelId)
                    ->where('spending', $isSpending)
                    ->get()
                    ->count();

        return $data;
    }

    public function createWallet($userId, $currency = 'IDR', $type = 1){
        if ($type == 1) {
            $locker = $userId.'-'.$currency;
        } else {
            $locker = $userId.'-'.$currency.'-'.$type;
        }

        $this->wallet->user_id = $userId;
        $this->wallet->currency = $currency;
        $this->wallet->locker = $locker;
        $this->wallet->type = $type;
        $this->wallet->save();

        return $this->wallet;
    }

    public function addBalance ($walletId, $amount){
        $result = $this->wallet->where('id', $walletId)->increment('balance', $amount);
        return $result;
    }

    public function minBalance ($walletId, $amount){
        $result = $this->wallet->where('id', $walletId)->decrement('balance', $amount);
        return $result;
    }

    public function getWalletLimit($walletId){
        $walletLimit = $this->wallet
                        ->join('wallet.wallet_limits', 'wallet.wallets.id', '=', 'wallet.wallet_limits.wallet')
                        ->leftJoin('wallet.wallet_transactions', function ($join) {
                            $join->on('wallet.wallets.id', '=', 'wallet.wallet_transactions.wallet_id')
                                ->whereDate('wallet.wallet_transactions.created_at',Carbon::now()->format('Y-m-d'));
                        })
                        ->select(
                            'wallet.wallet_limits.withdraw_daily',
                            'wallet.wallet_limits.transfer_daily',
                            'wallet.wallet_limits.payment_daily',
                            'wallet.wallet_limits.topup_daily',
                            'wallet.wallet_limits.switching_max',
                            'wallet.wallet_limits.max_balance',
                            'wallet.wallet_limits.transaction_monthly',
                            'wallet.wallet_limits.free_withdraw',
                            'wallet.wallet_limits.max_group_transfer',
                            'wallet.wallet_limits.max_group_withdraw'
                        )
                        ->selectRaw(
                            '
                            sum(wallet.wallet_transactions.amount) trans,
                            sum(case when wallet.wallet_transactions.note =\'In\' then wallet.wallet_transactions.amount else 0 end) trans_in,
                            sum(case when wallet.wallet_transactions.note =\'Out\' then wallet.wallet_transactions.amount else 0 end) trans_out,
                            sum(case when wallet.wallet_transactions.transaction_type =\'PPOB\' then wallet.wallet_transactions.amount else 0 end) payment,
                            sum(case when wallet.wallet_transactions.transaction_type =\'Withdraw\' then wallet.wallet_transactions.amount else 0 end) withdraw,
                            sum(case when wallet.wallet_transactions.transaction_type =\'Transfer\' then wallet.wallet_transactions.amount else 0 end) transfer
                            '
                        )
                        ->where('wallet.wallets.id',$walletId)
                        ->groupBy(
                            'wallet.wallet_limits.withdraw_daily',
                            'wallet.wallet_limits.transfer_daily',
                            'wallet.wallet_limits.payment_daily',
                            'wallet.wallet_limits.topup_daily',
                            'wallet.wallet_limits.switching_max',
                            'wallet.wallet_limits.max_balance',
                            'wallet.wallet_limits.transaction_monthly',
                            'wallet.wallet_limits.free_withdraw',
                            'wallet.wallet_limits.max_group_transfer',
                            'wallet.wallet_limits.max_group_withdraw'
                        )
                        ->first();

        $data = [
            'balance' => [
                'current' => (int) $walletLimit->balance,
                'hold' => (int) $walletLimit->hold,
                'limit' => (int) $walletLimit->max_balance,
            ],
            'transaction' => [
                'current' => (int) $walletLimit->trans,
                'limit' => (int) $walletLimit->transaction_monthly,
                'in' => [
                    'current' => (int) $walletLimit->trans_in,
                    'limit' => (int) $walletLimit->max_balance,
                ],
                'out' => [
                    'current' => (int) $walletLimit->trans_out,
                    'limit' => '~'
                ]
            ],
            'voucher' => [
                'current' => 0,
                'min' => 0,
                'limit' => 0
            ],
            'withdraw' => [
                'current' => (int) $walletLimit->withdraw,
                'min' => 0,
                'limit' => (int) $walletLimit->withdraw_daily
            ],
            'withdrawMonthlyFree' => [
                'current' => 0,
                'limit'   => (int) $walletLimit->free_withdraw
            ],
            'groupWithdraw' => [
                'limit' => (int) $walletLimit->max_group_withdraw
            ],
            'switching' => [
                'min' => 0,
                'max' => (int) $walletLimit->switching_max
            ],
            'transfer' => [
                'current' => (int) $walletLimit->transfer,
                'min' => 0,
                'limit' => (int) $walletLimit->transfer_daily
            ],
            'groupTransfer' => [
                'limit' => (int) $walletLimit->max_group_transfer
            ],
            'payment' => [
                'current' => (int) $walletLimit->payment,
                'limit' => (int) $walletLimit->payment_daily
            ]
        ];

        return $data;
    }
    public function getInstruction ($bank_code, $transaction = 'Topup', $language = 'ID'){
        $data = $this->bankInstruction
                    ->selectRaw(
                        '
                        accounts.bank_instruction.title as MainTitle, 
                        accounts.bank_instruction_lines.title as BodyMethod,
                        accounts.bank_instruction_lines.step_type as type,
                        accounts.bank_instruction_lines.step_value as value
                        '
                    )
                    ->join('accounts.bank_instruction_lines', 'accounts.bank_instruction_lines.instruction_id', '=', 'accounts.bank_instruction.id')
                    ->where('accounts.bank_instruction.bank_code', $bank_code)
                    ->where('accounts.bank_instruction.transaction', $transaction)
                    ->where('accounts.bank_instruction_lines.lang', $language)
                    ->orderby('accounts.bank_instruction_lines.title')
                    ->orderBy('accounts.bank_instruction_lines.steps')
                    ->get();

        if ($data->isEmpty()) {
            return false;
        }

        $result = [
            'title' => $data[0]['maintitle'],
            'options' => []
        ];

        $title = null;
        $index = 0;
        $no = 0;

        foreach ($data as $instruction) {
            if ($instruction['bodymethod'] != $title) {
                $result['options'][] = [
                    'title' => $instruction['bodymethod'],
                    'steps' => [
                        [
                            'type' => $instruction['type'],
                            'value' => $instruction['value']
                        ]
                    ]
                ];

                if ($no != 0) {
                    $index++;
                }
            }
            else{
                $result['options'][$index]['steps'][]= 
                [
                    'type' => $instruction['type'],
                    'value' => $instruction['value']
                ];
            }

            $no++;
            $title = $instruction['bodymethod'];
        }

        return $result;
    }

    public function createPassword($request)
    {
        $id = ServicePassword::insertGetId($request);
        return $id;
    }

    public function checkBalance($user_id = null)
    {        
        //wallet
        $user = Auth::id();
        if (isset($user_id)) {
            $user = $user_id;
        }
        $wallet = $this->getWalletByUser($user)->id;
        
        $data = $this->wallet
                    ->where('id',$wallet)
                    ->first();

        return ($data->balance - $data->hold);
    }
}
