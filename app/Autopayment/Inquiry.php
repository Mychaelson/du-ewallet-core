<?php


namespace App\Autopayment;

use App\Models\Ppob\PaymentSchedules;

/**
* Autopayment Inquiry
*/
class Inquiry extends Service
{
    protected $schedule;

    public function __construct(PaymentSchedules $schedule)
    {
        $this->schedule = $schedule;
    }

    public function run()
    {
        $service = config("autopayment.services.{$this->schedule->category}");
        \Log::error('service -- '.$service);
        $data = (new $service)->setParams($this->schedule)->inquiry();
        \Log::error('data -- '.$data);
        return $data;
    }
}
