<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    use HasFactory;

    protected $table = 'notif.notif_category';
    protected  $primaryKey = 'id';
}
