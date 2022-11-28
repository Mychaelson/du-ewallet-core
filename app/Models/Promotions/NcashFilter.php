<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class NcashFilter extends Model
{
    //

	protected $fillable = [
		'id',
		'ncashpromo_id',
		'type',
		'comparation',
		'condition_a',
		'condition_b',
	];

	public function ncash()
	{
		return $this->hasOne(\App\Models\Promotions\NcashPromotion::class,'id', 'ncashpromo_id');
	}
}
