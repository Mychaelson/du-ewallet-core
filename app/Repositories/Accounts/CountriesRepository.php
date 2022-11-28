<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Countries;

class CountriesRepository
{
	function __construct(private Countries $countries) {}

	public function getCountriesByName($name = 'all')
	{
		$countries = $this->countries->select(
						'id',
						'iso',
						'name',
						'nicename',
						'iso3',
						'numcode',
						'phonecode',
						'flag',
						'default',
					);

		if ($name != 'all')
			$countries = $countries->where('name', 'like', $name.'%');

		$countries = $countries->get();

		return $countries;
	}
}