<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\PasswordWrongHistories;

class PasswordWrongHistoriesRepository
{
	private $passwordWrongHistories;

	function __construct(PasswordWrongHistories $passwordWrongHistories)
	{
		$this->passwordWrongHistories = $passwordWrongHistories;
	}

	public function create($data)
	{
		$pwh = new PasswordWrongHistories();
		$pwh->insert($data);
	}

}