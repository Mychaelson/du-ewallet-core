<?php

namespace App\Autopayment\Jobs;

use App\Autopayment\Inquiry;
use App\Events\ReminderSchedule;
use App\Jobs\Job;
use App\Models\Ppob\PaymentSchedules;
use Carbon\Carbon;

class AutoInquiry extends Job
{
    /**
     * [$deleteWhenMissingModels description]
     * @var boolean
     */
    public $deleteWhenMissingModels = true;

    /**
     * [$schedule description]
     * @var [type]
     */
    public $schedule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PaymentSchedules $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $schedule = $this->schedule;

            $res = (new Inquiry($schedule))->run();
            if($res && $res->status === 'inquiry'){
                $schedule->update([
                    'on_schedule' => 0,
                    'status' => 1,
                    'price' => $res->total,
                    'transaction_id' => $res->id,
                    'last_inquiry' => Carbon::now()
                ]);
                event(new ReminderSchedule($schedule));
            }
        } catch (\Exception $e) {
            $schedule->update(['on_schedule' => 0, 'note' => $e->getMessage()]);
        }
    }
}
