<?php

namespace App\Notifications\Accounts;

use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class OTPNotification extends Notification
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        $message = trans('notifications.otp-notifications', ['token' => $this->data['token']]);
        return (new TwilioSmsMessage())->content($message);
    }
}
