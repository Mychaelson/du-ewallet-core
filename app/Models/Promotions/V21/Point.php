<?php

namespace App\Models\Promotions\V21;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    //

    protected $table = "promotions.points";
    protected $fillable = [
        'name',
        'merchant',
        'product',
        'nominal',
        'point',
        'point_spread',
        'filter',
        'start_at',
        'end_at',
        'stopped_at',
        'status',
        'call_endpoint',
    ];


    protected $dates = [
        'start_at',
        'end_at',
        'stopped_at',
    ];

    protected $casts = [
        'filter' => 'json',
        'product' => 'integer',
        'nominal' => 'integer',
        'point_spread' => 'integer',
        'point' => 'integer',
        'merchant' => 'integer'
    ];
    
}
