<?php

namespace App\Notifications\Accounts;

use Illuminate\Notifications\Notification;
use NotificationChannels\AwsSns\SnsChannel;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient; 

class OTPNotificationSNS extends Notification
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return $this->toSns($notifiable);
    }

    public function toSns($notifiable)
    {
        $message = trans('notifications.otp-notifications', ['token' => $this->data['token']]);
        $SnSclient = new SnsClient([
            'region' => env('AWS_SNS_REGION', ''),
            'version' => env('AWS_SNS_VERSION', '')
        ]);
        
        try {
            $result = $SnSclient->publish([
                'Message' => $message,
                'PhoneNumber' => $this->data['username'],
                'MessageType' => 'Transactional',
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }
}
