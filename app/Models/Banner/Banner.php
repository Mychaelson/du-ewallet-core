<?php

namespace App\Models\Banner;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    
    protected $table = 'banner.banners';

    protected $fillable = [
        'id','image','cover','title','highlight','terms','activity','label','web','phone','email','time_start',
        'time_end','group','params','status','updated','created'
    ];
}
