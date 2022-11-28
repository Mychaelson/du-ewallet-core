<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\PhoneChanges;

class PhoneChangesRepository
{
	private $phoneChanges;

	function __construct(PhoneChanges $phoneChanges)
	{
		$this->phoneChanges = $phoneChanges;
	}

	public function create($data)
	{
		$this->phoneChanges->insert($data);
	}

	public function getLatestPhoneChangesByUserId($userId)
	{
		$change = $this->phoneChanges->select(
					'id',
					'user_id',
					'phone',
					'phone_code',
					'progress'
				)
				->where('user_id', $userId)
				->orderBy('id', 'desc')
				->first();

		return $change;
	}

	public function complete($id)
	{
		$this->phoneChanges->where('id', $id)->update(['progress' => 'completed']);
	}
}