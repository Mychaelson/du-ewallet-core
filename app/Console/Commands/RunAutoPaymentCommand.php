<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ppob\PaymentSchedules;
use Carbon\Carbon;

class RunAutoPaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'autopayment:payment';

     /**
      * The console command description.
      *
      * @var string
      */
     protected $description = 'Run auto payment on schedule';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d');

        $groupUsers = PaymentSchedules::where('payment_at', $now)->where('on_schedule', false)->where('status', 0)->groupBy('user_id')->select('user_id')->get();
        // dd($groupUsers);
        foreach ($groupUsers as $user) {
            $schedules = PaymentSchedules::join('accounts.users', 'accounts.users.id', '=', 'payment_schedules.users_id')
            ->where('payment_at', $now)->where('on_schedule', false)->where('payment_schedules.status', 0)->where('user_id', $user->user_id)->get();
            dd($schedules);
            $delay = 10; // in seconds
            foreach ($schedules as $row) {
                // $row->update(['on_schedule' => 1]);
                // dd($row);
                // $te = (new \App\Autopayment\Jobs\AutoPayment($row));

                // ->delay(Carbon::now()->addSeconds($delay))
                dispatch(
                    (new \App\Autopayment\Jobs\AutoPayment($row))->delay(Carbon::now()->addSeconds($delay))
                );
                $delay = $delay+10;
            }
        }
    }
}
