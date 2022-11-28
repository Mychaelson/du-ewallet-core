<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $table ='accounts.media';
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'filename',
        'extension',
        'mimetype',
        'filesize',
        'filepath',
        'url',
        'thumb',
        'type',
        'disk',
        'publish',
        'album_id',
        'name',
        'description',
        'user_id',
        'group_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\OAuth\User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class, 'group_id');
    }
}
