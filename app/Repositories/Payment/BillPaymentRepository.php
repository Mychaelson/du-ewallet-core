<?php

namespace App\Repositories\Payment;

use App\Models\Payment\BillPayment;

class BillPaymentRepository
{
	private $billPayment;

	function __construct(BillPayment $billPayment)
	{
		$this->billPayment = $billPayment;
	}

	public function get($where)
	{
		$bill = $this->billPayment->where($where)->get();

		return $bill;
	}

	public function insert($create)
	{
		$id = $this->billPayment->insertGetId($create);
		$bill = $this->billPayment->where('id', $id)->first();

		return $bill;
	}
}
