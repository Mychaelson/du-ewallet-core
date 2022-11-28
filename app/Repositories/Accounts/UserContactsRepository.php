<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\UserContacts;

class UserContactsRepository
{

	function __construct(private UserContacts $userContacts)
	{}

	public function create($data)
	{
		$this->userContacts->insert($data);
	}

	public function update($id, $data)
	{
		$this->userContacts->where('id', $id)->update($data);
	}

	public function getUserContactsByUserIdAndPhone($userId, $phone)
	{
		$userContacts = $this->userContacts->select(
							'id',
							'user_id',
							'name',
							'phone',
							'meta',
						)
					   ->where('user_id', $userId)
					   ->where('phone', $phone)
					   ->first();

		return $userContacts;
	}
}
