<?php

namespace App\Autopayment\Jobs;

use App\Autopayment\Inquiry;
use App\Autopayment\Payment;
use App\Events\PendingPushTransactionEvent;
use App\Events\ReminderSchedule;
use App\Jobs\Job;
use App\Models\Ppob\PaymentSchedules;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AutoPayment extends Job
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
     * [$errors description]
     * @var [type]
     */
    public $errors;

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

            Log::error('working well');

            // inquiry dulu baru payment
            $inq = (new Inquiry($schedule))->run();

            if($inq && $inq->status === 'inquiry'){

                $schedule->update([
                    'on_schedule' => 1,
                    'status' => 1,
                    'transaction_id' => $inq->id,
                    'last_inquiry' => Carbon::now()
                ]);

                if($schedule->transaction){
                    // check balance && make payment wallet
                    if($this->middlewareWallet($schedule)){
                        $this->payment($schedule);
                    }
                }

                $schedule->update(['on_schedule' => 0, 'status' => 0, 'note' => $this->errors]);
            }
        } catch (\Exception $e) {
            $schedule->update(['on_schedule' => 0, 'note' => $e->getMessage()]);
            Log::error($e->getMessage());
        }
    }

    public function payment($schedule)
    {
        $res = (new Payment($schedule))->run();
        if($res){
            $nextSchedule = $schedule->payment_at;
            if($schedule->repeat){
                $nextSchedule = Carbon::createFromFormat('Y-m-d', $nextSchedule)->addMonth();
            }
            $schedule->update([
                'on_schedule' => 0,
                'status' => 0,
                'price' => $res->total,
                'transaction_id' => $res->id,
                'last_inquiry' => Carbon::now(),
                'payment_at' => $nextSchedule,
                'last_payment' => Carbon::now(),
                'note' => $this->errors
            ]);

            $transaction = $schedule->transaction;
            event(new PendingPushTransactionEvent($transaction));
        }
    }

    public function middlewareWallet($schedule)
    {
        $action = $this->createActionPayment($schedule);
        $trans = $schedule->transaction;
        if($action){
            $data = [
                'label' => 1,
                'currency' => 'IDR',
                'promo' => null,
                'discount' => null,
                'invoice' => $trans->order_id,
                'cart' => $trans->order_id,
                'password' => $action->password,
                'amount' => $trans->total,
                'description' => $trans->product_snap['name']?? '',
                'reff' => $trans->order_id,
                'ncash' => 0,
                'action' => $action->id
            ];

            $res = $this->exec('/payment/commit', $data);
            if($res->success){
                return true;
            }
        }
        return false;
    }

    public function createActionPayment($schedule)
    {
        $res = $this->exec('/cred/action-password', [
            'service' => $schedule->wallet_id,
            'password' => $schedule->wallet_hash,
            'user' => $schedule->user_id
        ]);

        if($res->success){
            return $res->data;
        }

        return false;
    }

    public function exec($url, $data = [])
    {
        $http = new Client;
        $req = $http->request('POST', config('config.host.wallet'). '/api'. $url, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => generate_client_token(),
            ],
            'json' => $data,
            'http_errors' => false,
        ]);

        $body = (string) $req->getBody();
        $data = json_decode($body);

        if($req->getStatusCode() === 200){

            if(! $data->success)
                $this->errors = $data->message;

            return $data;
        }

        return false;
    }
}
