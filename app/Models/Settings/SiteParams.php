<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteParams extends Model
{
    use HasFactory;
    protected $table = 'setting.site_params';

	protected $fillable = [
		'name',
        'type',
        'group',
        'value'
	];
}
