<?php

namespace App\Models\Docs;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    
    protected $table = 'docs.document';

    protected $fillable = [
        'id','title','slug','locale','version'
    ];

    protected $casts = ['id' => 'string'];
}
