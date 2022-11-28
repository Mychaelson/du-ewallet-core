<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\UserAddress;
use Illuminate\Http\Request;

class UserAddressRepository
{
	private $uaRepository;

	function __construct(UserAddress $uaRepository)
	{
		$this->uaRepository = $uaRepository;
	}

	public function getUserMainAddressByUserId($userId)
	{
		$mainAddress = $this->uaRepository->select(
							'accounts.user_address.id',
							'accounts.user_address.user_id',
							'accounts.user_address.name',
							'accounts.user_address.phone',
							'accounts.user_address.is_main',
							'accounts.user_address.address',
							'accounts.user_address.postal_code',
							'accounts.user_address.updated_at',
							'accounts.countries.name as country_name',
							'accounts.provinces.name as province',
							'accounts.cities.name as city',
							'accounts.subdistricts.name as subdistrict',
							'accounts.villages.name as village',
						)
						->leftJoin('accounts.provinces', 'accounts.user_address.province_id', '=', 'accounts.provinces.id')
						->leftJoin('accounts.countries', 'accounts.provinces.country_id', '=', 'accounts.countries.id')
						->leftJoin('accounts.cities', 'accounts.user_address.city_id', '=', 'accounts.cities.id')
						->leftJoin('accounts.subdistricts', 'accounts.user_address.subdistrict_id', '=', 'accounts.subdistricts.id')
						->leftJoin('accounts.villages', 'accounts.user_address.village_id', '=', 'accounts.villages.id')
						->where('accounts.user_address.user_id', $userId)
						->where('accounts.user_address.is_main', 1)
						->first();

		return $mainAddress;
	}

	// public function checkUserMainAddress

	public function addAddress ($addressInfo){
		$this->uaRepository->insert($addressInfo);
	}

	public function updateIsMainAddress ($userId){
		$this->uaRepository->where('user_id', $userId)->update(['is_main' => 0]);
	}
}