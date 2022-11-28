<?php

namespace App\Macros\Accounts;

use App\Repositories\Accounts\BanksRepository;
use App\Repositories\Accounts\BankAccountsRepository;

class RegisterVirtualAccountsMacro
{
	function __construct(
        private BanksRepository $banksRepository,
		private BankAccountsRepository $baRepository
	)
	{}

	public function handle($data)
	{
        $user = $data['user'];

        //should get banks with VA
        $banks = $this->banksRepository->getBanksByBankName('% VA');
        foreach ($banks as $key => $bank) {
            //should check if bank account exist first
            $validateBank = $this->baRepository->getUserBankAccountsAndBankIdByUserId($user->id, $bank->id);
            if (is_null($validateBank)) {
                $bankAccounts[] = array(
                    'user_id' => $user->id,
                    'bank_id' => $bank->id,
                    'account_name' => $user->name ?? $user->phone,
                    'account_number' => $bank->code.$user->phone,
                    'is_main' => 0,
                    'is_active' => 1,
                    'is_verify' => 1,
                    'is_virtual' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->baRepository->create($bankAccounts);
            } else {
                $bankAccounts[] = $validateBank;
                $updates = array(
                    'account_name' => $user->name ?? $user->phone,
                    'account_number' => $bank->code.$user->phone,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->baRepository->update($updates, $validateBank->id);
            }
        }

        //should insert
        $data['response']['message'] = trans('messages.virtual-accounts-added');
        $data['response']['data'] = $bankAccounts;
        return $data;
	}
}