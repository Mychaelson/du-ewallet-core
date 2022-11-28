<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

	protected $table = 'ticket.ticket_category';

	protected $fillable = [
		"id",
		"name",
		"parent",
		"priority",
		"activity",
		"status",
		"updated",
		"created"
	];

	protected $with = ['parent'];

	public function parent()
    {
        return $this->belongsTo(\App\Models\Ticket\Category::class, 'parent', 'id');
    }
}
