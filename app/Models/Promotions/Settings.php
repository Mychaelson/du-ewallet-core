<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
	protected $table = "promotions.settings";

	protected $fillable = [
        'key',
        'value'
	];
}
