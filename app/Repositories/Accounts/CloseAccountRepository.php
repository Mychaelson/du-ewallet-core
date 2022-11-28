<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\CloseAccount;

class CloseAccountRepository
{
	function __construct(
		private CloseAccount $closeAccount
	)
	{}

	public function create($data)
	{
		$this->closeAccount->user_id = $data['user_id'];
		$this->closeAccount->status = $data['status'];
		$this->closeAccount->emoticon = $data['emoticon'];
		$this->closeAccount->content = $data['content'];
		$this->closeAccount->approval_by = null;
		$this->closeAccount->approved_at = null;
		$this->closeAccount->reason = null;
		$this->closeAccount->meta = null;
		$this->closeAccount->created_at = date('Y-m-d H:i:s');
		$this->closeAccount->updated_at = date('Y-m-d H:i:s');
		$this->closeAccount->save();
		return $this->closeAccount;
	}

	public function update($userId, $data)
	{
		$this->closeAccount->where('user_id', $userId)->update($data);
	}

	public function getCloseAccountByUserId($userId)
	{
		$userBanks = $this->closeAccount
					   ->where('user_id', $userId)
					   ->first();

		return $userBanks;
	}
}