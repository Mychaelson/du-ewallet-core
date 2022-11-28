<?php

namespace App\Repositories\Payment;

use App\Models\Payment\Card;

class CardRepository
{
	private $card;

	function __construct(Card	 $card)
	{
		$this->card = $card;
	}

	public function get($where)
	{
		$card = $this->card->where($where)->get();

		return $card;
	}

	public function getFirst($where)
	{
		$card = $this->card->where($where)->first();

		return $card;
	}

	public function create($create)
	{
		$id = $this->card->insertGetId($create);
		$card = $this->card->where('id', $id)->get();

		return $card;
	}

	public function getPaginate($where, $page, $perpage)
	{
		$card = $this->card->where($where)->orderBy('created_at', 'asc')->skip($page)->take($perpage)->get();

		return $card;
	}

	public function update($where, $update)
	{
		$this->card->where($where)->update($update);

		return;
	}
}
