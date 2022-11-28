<?php

namespace App\Repositories\Ppob;

use App\Models\Ppob\DigitalProducts;

class DigitalProductsRepository
{
	function __construct(
		private DigitalProducts $digitalProductsRepository
		)
	{
	}

	public function orWhere($orWhere1, $orWhere2)
	{
		$data = $this->digitalProductsRepository->where($orWhere1)->orWhere($orWhere2)->first();

		return $data;
	}

	public function first($where)
	{
		$data = $this->digitalProductsRepository->where($where)->limit(1)->get();

		return $data;
	}

	public function get($where)
	{
		$data = $this->digitalProductsRepository->where($where)->get();

		return $data;
	}

}
