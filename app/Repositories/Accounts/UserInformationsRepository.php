<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\UserInformations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserInformationsRepository
{
	private $uiRepository;

	function __construct(UserInformations $uiRepository)
	{
		$this->uiRepository = $uiRepository;
	}

	public function create($userId)
	{
		$this->uiRepository->user_id = $userId;
		$this->uiRepository->save();
		return $this->uiRepository;
	}

	public function update($updates, $userId)
	{
		$this->uiRepository->where('user_id', $userId)->update($updates);
	}

	public function getUserInformationByUserId($userId)
	{
		$userInfo = $this->uiRepository->where('user_id', $userId)->first();

		return $userInfo;
	}

	public function getUserDeviceInfo($user_id)
	{
		$userDevices = DB::table('accounts.user_devices')->where('user_id', $user_id)->whereNotNull('device_token')->pluck('device_token')->all();
		
		return $userDevices;
	}
}