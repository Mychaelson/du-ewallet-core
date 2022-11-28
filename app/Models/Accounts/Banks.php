<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Banks extends Model
{
    
    protected $table = 'accounts.banks';

    protected $appends = ['aid'];

    protected function aid(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['id'],
        );
    }
    
}
