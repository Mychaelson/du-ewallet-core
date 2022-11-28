<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    
    protected $table = 'docs.help';

    protected $fillable = [
        'id','user','category','locale','group','title','content','keywords'
    ];
    
    protected $casts = ['id' => 'string'];
}
