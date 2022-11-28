<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    //
    protected $table = "promotions.fund_history";

	protected $fillable = [
		"amount",
		"type",
		"note",
		"agreement_id",
		"transaction_id",
		"merchant_id"
	];

	public function fund()
	{
		return $this->belongsTo(\App\Models\Promotions\Agreement::class, 'agreement_id', 'id');
	}
}
