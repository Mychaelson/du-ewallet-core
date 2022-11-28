<?php

namespace App\Autopayment;

use App\Models\Ppob\PaymentSchedules;

/**
* Autopayment Payment
*/
class Payment extends Service
{

    protected $schedule;

    public function __construct(PaymentSchedules $schedule)
    {
        $this->schedule = $schedule;
    }

    public function run()
    {
        $service = config("autopayment.services.{$this->schedule->category}");
        $data = (new $service)->setParams($this->schedule)->payment();
        return $data;
    }
}
