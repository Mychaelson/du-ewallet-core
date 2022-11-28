<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketComment extends Model
{
    //

	protected $table = 'ticket.ticket_comment';

	public function ticket()
    {
        return $this->hasOne(\App\Models\Ticket\Ticket::class, 'id', 'ticket')->select('id', 'subject', 'category', 'category_sub', 'body', 'attachment', 'updated_at', 'created_at');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\Accounts\Users::class, 'id', 'user')->select('id', 'name', 'nickname', 'phone', 'avatar', 'main_device', 'main_device_name', 'blood_type','marital_status','religion','group_id','watch_status','check_extra_password','whatsapp_active');
    }

    public function admin()
    {
        return $this->hasOne(\App\Models\Backoffice\User::class, 'id', 'admin');
    }
}
