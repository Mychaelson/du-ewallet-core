<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Banks;
use App\Models\Accounts\CompanyBankAccounts;

class BanksRepository
{
	private $banks;

	function __construct(Banks $banks)
	{
		$this->banks = $banks;
	}

	public function getBanksByBankName($name)
	{
		$userBanks = $this->banks;

		if ($name != 'all'){
			$userBanks = $userBanks->where('name', 'like', '%'. $name .'%');
		}

		$userBanks = $userBanks->get();

		return $userBanks;
	}

	public function getBankById ($bankId) {
		$bank = $this->banks->where('accounts.banks.id', $bankId)
												->join('accounts.company_bank_accounts', 'accounts.banks.id', '=', 'accounts.company_bank_accounts.bank_id')
												// ->Join("accounts.company_bank_accounts as b", function ($join) {
												// 	$join->on("b.bank_id", "=", "accounts.banks.id");
												// })
												->select(
													'accounts.banks.id',
													'accounts.banks.code',
													'accounts.banks.name',
													'accounts.company_bank_accounts.account_name',
													'accounts.company_bank_accounts.account_number',
													'accounts.company_bank_accounts.payment_gateway',
													'accounts.company_bank_accounts.payment_method_code',
												)
												->first();
		return $bank ? $bank->toArray() : $bank;
	}

	public function getBankCompany () {
		$banks = $this->banks;
		
		$company = CompanyBankAccounts::select('bank_id')->get();

		$banks = $banks->whereIn('banks.id', $company)
									->join('accounts.company_bank_accounts', 'accounts.banks.id', '=', 'accounts.company_bank_accounts.bank_id')
									->selectRaw(
										'banks.id, code as bank_code, name as bank_name, image as bank_icon, account_type, description as bank_description, account_name, account_number'
									)
									->get();

		return $banks;
	}
}