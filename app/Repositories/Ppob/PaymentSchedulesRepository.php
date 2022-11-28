<?php

namespace App\Repositories\Ppob;

use App\Models\Ppob\PaymentSchedules;

class PaymentSchedulesRepository
{
	function __construct(
		private PaymentSchedules $paymentSchedules
		)
	{
	}


	public function paginate($where, $request)
	{
		$data = $this->paymentSchedules
   	->join('ppob.digital_transactions', 'ppob.digital_transactions.id', '=', 'payment_schedules.transaction_id')
		->where($where)->whereIn('payment_schedules.status', [0,1]);

		if (isset($request->q))
			$data = $data->where('name', 'LIKE', '%'.$request->q.'%');

		$data = $data->orderBy('name')->paginate(12);
		return $data;
	}

	public function update($where, $update)
	{
		$updated = $this->paymentSchedules->where($where)->update($update);

		return $updated;
	}

	public function first($where)
	{
		$updated = $this->paymentSchedules
		->join('digital_transactions', 'digital_transactions.id', '=', 'payment_schedules.transaction_id')
		->where($where)->limit(1)->get();

		return $updated;
	}

	public function updateOrCreate($where, $update)
	{
		$updated = $this->paymentSchedules->updateOrCreate($where, $update);

		return collect([$updated]);
	}

}
