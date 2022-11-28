<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model
{
    protected $table = 'accounts.user_devices';

    protected $fillable = [
        'device_token',
        'device_name',
        'user_id',
        'location',
        'login_count'
    ];
}
