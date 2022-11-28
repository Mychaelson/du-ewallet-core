<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    //
    protected $table = "promotions.transaction_log";

	protected $fillable = [
		"promotion_id",
		"promotion_user_id",
		"created_by",
		"promotion_type",
		"transaction_type",
		"uq_transaction_id",
		"amount",
		"name",
		"expired_at",
		"transaction_detail"
	];

	protected $dates = [
        'created_at',
        'updated_at',
        'expired_at',
    ];

	protected $casts = [
		'transaction_detail' => 'json'
	];

	public function promotion()
	{
		return $this->belongsTo(\App\Models\Promotions\Promotion::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\Promotions\PromotionUser::class, 'promotion_user_id');
	}
}
