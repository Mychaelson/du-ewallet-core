<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class PromotionRevision extends Model
{
    //
    protected $table = "promotions.promotion_revision";

	protected $fillable = [
		"promotion_id",
		"reply_to",
		"message",
		"attachment",
		"read_at",
		"replied_at"
	];

	protected $casts = [
		'attachment' => 'json'
	];

}
