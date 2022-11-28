<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class NcashPromotion extends Model
{
    //

	protected $table = 'promotions.ncash_promotion';

	protected $fillable = [
		'id',
		'merchant',
		'product',
		'created_by',
		'name',
		'slug',
		'to_spread',
		'spread_count',
		'code',
		'percentage',
		'max_amount',
		'start_date',
		'end_date',
		'can_claim_start',
		'can_claim_end',
		'terms',
		'meta',
		'terminated_end_date',
		'status',
	];

	protected $casts = [
		'id' => 'integer',
		'merchant' => 'integer',
		'product' => 'integer',
		'created_by' => 'integer',
		'name' => 'string',
		'slug' => 'string',
		'to_spread' => 'integer',
		'spread_count' => 'integer',
		'code' => 'string',
		'percentage' => 'integer',
		'max_amount' => 'integer',
		'start_date' => 'date',
		'end_date' => 'date',
		'can_claim_start' => 'time',
		'can_claim_end' => 'time',
		'terms' => 'string',
		'meta' => 'json',
		'terminated_end_date' => 'datetime',
		'status' => 'integer',
		'claim_p_day' => 'integer',
		'claim_p_day_user' => 'integer',
		'max_amount_claim_user' => 'double',
	];

	protected $hidden = [
		'budget',
		'budget_used',
		'code',
		'created_by',

	];

	public function filters()
	{
		return $this->hasMany(\App\Models\Promotions\NcashFilter::class,'id', 'ncashpromo_id');
	}
}
