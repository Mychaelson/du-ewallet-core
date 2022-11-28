<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\BankAccounts;

class BankAccountsRepository
{
	private $bankAccounts;

	function __construct(BankAccounts $bankAccounts)
	{
		$this->bankAccounts = $bankAccounts;
	}

	public function create($data)
	{
		$this->bankAccounts->insert($data);
	}

	public function update($data, $id)
	{
		$this->bankAccounts->where('id', $id)->update($data);
	}

	public function getUserBankAccountsByUserId($userId)
	{
		$userBanks = $this->bankAccounts->select(
							'accounts.bank_accounts.id',
							'accounts.bank_accounts.user_id',
							'accounts.bank_accounts.bank_id',
							'accounts.bank_accounts.account_name',
							'accounts.bank_accounts.account_number',
							'accounts.bank_accounts.is_main',
							'accounts.bank_accounts.is_active',
							'accounts.bank_accounts.is_verify',
							'accounts.bank_accounts.is_virtual',
							'accounts.banks.code',
							'accounts.banks.name',
							'accounts.banks.image',
						)
					   ->join('accounts.banks', 'accounts.bank_accounts.bank_id', '=', 'accounts.banks.id')
					   ->where('accounts.bank_accounts.user_id', $userId)
					   ->get();

		return $userBanks;
	}

	public function getUserBankAccountsAndBankIdByUserId($userId, $bankId)
	{
		$userBank = $this->bankAccounts->select(
							'accounts.bank_accounts.id',
							'accounts.bank_accounts.user_id',
							'accounts.bank_accounts.bank_id',
							'accounts.bank_accounts.account_name',
							'accounts.bank_accounts.account_number',
							'accounts.bank_accounts.is_main',
							'accounts.bank_accounts.is_active',
							'accounts.bank_accounts.is_verify',
							'accounts.bank_accounts.is_virtual',
						)
					   ->where('accounts.bank_accounts.user_id', $userId)
					   ->where('accounts.bank_accounts.bank_id', $bankId)
					   ->first();

		return $userBank;
	}
}
