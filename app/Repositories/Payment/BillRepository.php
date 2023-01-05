<?php

namespace App\Repositories\Payment;

use App\Models\Payment\Bill;
use App\Repositories\Ppob\Base\PpobRepository;
use Carbon\Carbon;

class BillRepository
{
	private $bill;

	function __construct(Bill $bill, private PpobRepository $ppobRepository)
	{
		$this->bill = $bill;
	}

	public function search($where)
	{
		$bill = $this->bill->where($where)->get();

		return $bill;
	}

	public function get($where)
	{
		$bill = $this->bill->where($where)->first();

		return $bill;
	}

	public function getByOrWhere($where, $orWhere1, $orWhere2)
	{
		$bill = $this->bill->where(function ($query) use ($orWhere1, $orWhere2) {
				$query->where($orWhere1)
							->orWhere($orWhere2);
		})->where($where)->first();


		return $bill;
	}

	public function update($where, $update)
	{
		$data = $this->bill->where($where)->update($update);

		return $data;
	}

	public function createBill($order_id)
    {
        $trans = $this->ppobRepository->getDetailByOrder_id($order_id);

        if (!$trans) return;

        $name = json_decode($trans->product_snap)->name ?? '';
        $invoice = [
            'description' => $name,
            'invoice' => $trans->order_id,
            'amount' => $trans->total,
            'expires' => gmdate('Y-m-d H:i:s', strtotime('+2 hours')),
            'user' => $trans->user_id,
            'merchant' => 1,
            'wallet' => 'local',
            'currency' => 'IDR',
            'status' => 2,
            'callback' => '',
            'reason' => '',
            'paid' => 0,
            'cashback' => 0.00,
						'created_at' => now(),
						'updated_at' => now()
        ];

			$id = $this->bill->insertGetId($invoice);

			return $id;
    }
	
	public function createBill($order_id)
    {
        $trans = $this->ppobRepository->getDetailByOrder_id($order_id);

        if (!$trans) return;

        $name = json_decode($trans->product_snap)->name ?? '';
        $invoice = [
            'description' => $name,
            'invoice' => $trans->order_id,
            'amount' => $trans->total,
            'expires' => gmdate('Y-m-d H:i:s', strtotime('+2 hours')),
            'user' => $trans->user_id,
            'merchant' => 1,
            'wallet' => 'local',
            'currency' => 'IDR',
            'status' => 2,
            'callback' => '',
            'reason' => '',
            'paid' => 0,
            'cashback' => 0.00,
						'created_at' => now(),
						'updated_at' => now()
        ];

			$id = $this->bill->insertGetId($invoice);

			return $id;
    }

	public function createTransactionBill ($invoice_no, $namespace){
		$trans = $this->ppobRepository->getTransactionByInvoiceNo($invoice_no);

		if (!isset($trans)) {
			return;
		}

		$product = $this->ppobRepository->findProductInfo($trans->product_code);

		if (!isset($product)) {
			return;
		}

		$invoice = [
			'description' => $product->name,
			'invoice' => $trans->invoice_no,
			'amount' => $trans->total,
			'expires' => Carbon::now()->addHours(2),
			'user' => $trans->user_id,
			'merchant' => 1,
			'wallet' => 'local',
			'currency' => 'IDR',
			'status' => 2,
			'callback' => '',
			'reason' => '',
			'paid' => 0,
			'cashback' => 0.00,
			'payment_service' => $namespace,
			'created_at' => now(),
			'updated_at' => now()
		];

		$id = $this->bill->insertGetId($invoice);

		return $id;
	}

	public function getBill($invoice_no){
		$data = $this->bill->where('invoice', $invoice_no)->first();

		return $data;
	}
}
