<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //

	protected $table = 'ticket.ticket';

	public function category()
    {
        return $this->hasOne(\App\Models\Ticket\Category::class, 'id', 'category')->select('id', 'name', 'parent', 'priority', 'activity', 'status', 'updated_at', 'created_at');
    }
    public function categorySub()
    {
        return $this->hasOne(\App\Models\Ticket\Category::class, 'id', 'category_sub')->select('id', 'name', 'parent', 'priority', 'activity', 'status', 'updated_at', 'created_at');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\Accounts\Users::class, 'id', 'user')->select('id', 'name', 'nickname', 'phone', 'avatar', 'main_device', 'main_device_name', 'blood_type','marital_status','religion','group_id','watch_status','check_extra_password','whatsapp_active');
    }

	public function comments()
    {
        return $this->hasMany(\App\Models\Ticket\TicketComment::class, 'ticket', 'id');
    }
}
