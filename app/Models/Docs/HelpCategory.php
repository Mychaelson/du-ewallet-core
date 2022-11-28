<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Model;

class HelpCategory extends Model
{
    
    protected $table = 'docs.help_category';

    protected $fillable = [
        'id','user','name','group','slug','locale','icon'
    ];
    
    protected $casts = ['id' => 'string'];
}
