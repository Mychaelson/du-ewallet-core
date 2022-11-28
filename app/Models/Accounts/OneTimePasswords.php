<?php

namespace App\Models\Accounts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OneTimePasswords extends Model
{
    use Notifiable;

    protected $table = 'accounts.one_time_passwords';

    public function routeNotificationForTwilio()
    {
        return '+'.$this->username;
    }

    public function isExpired()
    {
        return Carbon::parse($this->expires_at)->isPast();
    }
}
