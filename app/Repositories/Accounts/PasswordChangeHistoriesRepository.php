<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\PasswordChangeHistories;

class PasswordChangeHistoriesRepository
{
	private $passwordChangeHistories;

	function __construct(PasswordChangeHistories $passwordChangeHistories)
	{
		$this->passwordChangeHistories = $passwordChangeHistories;
	}

  public function getlast ($userId) {
		$userRegister = auth()->user()->created_at;
    $lastPasswordChange = $this->passwordChangeHistories->where('user_id', $userId)->orderBy('created_at', 'desc')->first();
		// dd($lastPasswordChange);
		return $lastPasswordChange ? $lastPasswordChange->created_at : $userRegister;
  }

	public function record ($userDeviceInfo){
		$this->passwordChangeHistories->insert($userDeviceInfo);
	}

}