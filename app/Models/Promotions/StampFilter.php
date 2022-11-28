<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class StampFilter extends Model
{
    //

	protected $table = 'promotions.stamp_filter';
	protected $fillable = [
		'id',
		'ncashpromo_id',
		'multiply',
		'stamp',
		'comparation',
		'condition_a',
		'condition_b',
	];

	protected $casts = [
		'id' => 'integer',
		'ncashpromo_id' => 'integer',
		'multiply' => 'boolean',
		'stamp' => 'integer',
		'comparation' => 'string',
		'condition_a' => 'string',
		'condition_b' => 'string',
	];

	public function catalogue()
	{
		return $this->hasOne(\App\Models\Promotions\StampCatalogue::class,'id', 'ncashpromo_id');
	}
}
