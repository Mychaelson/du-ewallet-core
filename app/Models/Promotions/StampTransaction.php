<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class StampTransaction extends Model
{
    //

    protected $table = "promotions.stamp_transaction";
    protected $fillable = [
        'promotion_user_id',
        'stampcat_id',
        'stamp',
        'type',
        'identifier',
        'stampfilter_id',
    ];


    protected $casts = [
        'promotion_user_id' => 'integer',
        'stampcat_id' => 'integer',
        'stamp' => 'integer',
        'type' => 'integer',
        'identifier' => 'integer',
    ];
    
}
