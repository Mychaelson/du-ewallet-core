<?php

namespace App\Models\Track;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'track.activities';

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'activity_screen',
        'open_time',
        'leave_time',
        'next_activity_screen',
    ];
}
