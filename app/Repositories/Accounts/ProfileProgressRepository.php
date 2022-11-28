<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\ProfileProgress;

class ProfileProgressRepository
{
	private $profileProgress;

	function __construct(ProfileProgress $profileProgress)
	{
		$this->profileProgress = $profileProgress;
	}

	public function create($userId)
	{
		$this->profileProgress->user_id = $userId;
		$this->profileProgress->save();
		return $this->profileProgress;
	}

	public function getUserProfileProgressByUserId($userId)
	{
		$userProfile = $this->profileProgress->select(
							'accounts.profile_progresses.basic',
							'accounts.profile_progresses.profile',
							'accounts.profile_progresses.contact',
							'accounts.profile_progresses.document',
							'accounts.profile_progresses.address',
							'accounts.profile_progresses.tax_information',
							'accounts.profile_progresses.recovery_security',
							'accounts.profile_progresses.updated_at',
						)
					   ->where('accounts.profile_progresses.user_id', $userId)
					   ->first();

		return $userProfile;
	}

	public function update($data, $userId)
	{
		$this->profileProgress->where('user_id', $userId)->update($data);
	}
}