<?php

namespace App\Repositories\Wallet;

use App\Models\Settings\SiteParams;
use App\Models\Wallet\WalletLimits;
use App\Models\Wallet\Wallets;
use App\Models\Wallet\WalletWithdraw;
use App\Models\Wallet\WalletWithdrawFee;

class WithdrawRepository
{
    public function __construct(
        private Wallets $wallet,
        private WalletLimits $limit,
        private WalletsTransactionsRepository $transactionsRepository,
        private WalletWithdraw $withdraw,
        private WalletWithdrawFee $withdrawFee,
        private SiteParams $siteParams,
    ) {
    }

    public function getWithdrawFee(): float
    {
        return $this->siteParams
            ->query()
            ->where('name', 'withdraw_admin_fee')
            ->value('value') ?? 0.00;
    }

    public function isNeedOTP($amount = 0): bool
    {
        $otpAmount = $this->siteParams
            ->query()
            ->where('name', 'otp_email_wd_amount')
            ->value('value') ?? 0.00;

        return floatval($otpAmount) < $amount;
    }

    public function getWithdrawFeeFromPGA($bankCode)
    {
        // TODO: Get withdraw fee from PGA
    }

    public function getWithdrawById($withdrawId)
    {
        return $this->withdraw->find($withdrawId);
    }

    public function getWithdrawByStatus($walletId, $status)
    {
        return $this->withdraw->query()
            ->where('wallet_id', $walletId)
            ->where('status', $status)
            ->get();
    }

    public function createWithdraw($walletId, $data)
    {
        $data = json_decode(json_encode($data));
        $this->withdraw->user_id = auth()->id();
        $this->withdraw->wallet_id = $walletId;
        $this->withdraw->label = $data?->label ?? 1;
        $this->withdraw->amount = $data->amount;
        $this->withdraw->location = $data?->location ?? null;
        $this->withdraw->bank_name = $data->bank->name;
        $this->withdraw->bank_code = $data->bank->code;
        $this->withdraw->bank_account_name = $data->bank->account->name;
        $this->withdraw->bank_account_number = $data->bank->account->number;
        $this->withdraw->pg_fee = $data?->pg_fee ?? 0.00;
        $this->withdraw->pg_informed = $data?->pg_informed ?? null;
        $this->withdraw->pg_confirmed = $data?->pg_confirmed ?? null;
        $this->withdraw->agent = $data?->agent ?? null;
        $this->withdraw->status = 2;
        $this->withdraw->notify = $data?->notify ?? [];
        $this->withdraw->save();

        $transId = $this->addToTransaction($this->withdraw);
        $this->withdraw->transaction_id = $transId;

        return $this->withdraw;
    }

    public function updatePgInfo($withdrawId, $data)
    {
        $this->withdraw = $this->withdraw->find($withdrawId);
        $this->withdraw->pg_fee = $data->pg_fee;
        $this->withdraw->pg_informed = $data->pg_informed;
        $this->withdraw->pg_confirmed = $data->pg_confirmed;
        $this->withdraw->save();

        return $this->withdraw;
    }

    public function updateBalance($walletId, $amount)
    {
        $data = $this->wallet->find($walletId);
        $data->decrement('balance', $amount);
    }

    public function createGroupWithdraw($walletId, $data)
    {
        foreach ($data->to as $withdrawData) {
            $this->createWithdraw($walletId, $withdrawData);
        }
    }

    public function getCurrentBalance($walletId): float
    {
        $data = $this->wallet->find($walletId);

        return $data->balance - $data->hold;
    }

    public function getMinWithdraw(): int
    {
        return $this->siteParams
            ->query()
            ->where('name', 'withdraw_min')
            ->value('value');
    }

    public function getMaxWithdraw(): int
    {
        return $this->siteParams
            ->query()
            ->where('name', 'withdraw_max')
            ->value('value');
    }

    public function getLimitWithdraw($walletId): int
    {
        return $this->limit
            ->query()
            ->where('wallet', $walletId)
            ->value('withdraw_daily');
    }

    public function getMaxWithdrawGroupMember($walletId): int
    {
        return $this->limit
            ->query()
            ->where('wallet', $walletId)
            ->value('withdraw_group_max') ?? 5;
    }

    public function getWithdrawMonthlyFree($walletId): int
    {
        return $this->limit
            ->query()
            ->where('wallet', $walletId)
            ->value('withdraw_monthly_free') ?? 0;
    }

    public function getCurrentTotalWithdrawDaily($walletId): int
    {
        $filter = [
            'wallet_id' => $walletId,
            'transaction_type' => 'Withdraw',
            'note' => 'Out',
            'created' => now()->format('Y-m-d'),
        ];

        $data = $this->transactionsRepository->getList($filter)->sum('amount');

        return $data;
    }

    public function isWithdrawLocked($walletId): bool
    {
        return $this->wallet
            ->query()
            ->where('id', $walletId)
            ->value('lock_wd') == 1;
    }

    private function addToTransaction($data)
    {
        $balance = $this->wallet->find($data->wallet_id)->balance;
        $location = ip2location(request()->ip())->fullAddress();
        $transId = $this->transactionsRepository->store([
            'label_id' => $data->label,
            'wallet_id' => $data->wallet_id,
            'reff_id' => $data->id,
            'amount' => $data->amount,
            'transaction_type' => 'Withdraw',
            'balance_before' => $balance,
            'location' => $location,
            'status' => 2,
            'note' => 'Out',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $transId;
    }
}
