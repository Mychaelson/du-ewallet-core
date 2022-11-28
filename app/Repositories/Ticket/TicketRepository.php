<?php

namespace App\Repositories\Ticket;


use Illuminate\Support\Facades\DB;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Category;
use App\Models\Ticket\TicketComment;


class TicketRepository
{

    public $status  = 'status';
    public $error   = 'error';
    public $user_data;
    public $sess;


    function getTickets($cond,$paginate) {

        $ticket = Ticket::where($cond)->withCount('comments as comments')->with('category')->with('categorySub')->with('user')->latest('updated_at')->simplePaginate($paginate);

        return $ticket;
    }

    
    function getOneTicket($cond) {

        $ticket = Ticket::where($cond)->with('category')->with('categorySub')->with('user')->first();

        return $ticket;
    }

    function checkScope($userId) {

        $scope = DB::table('oauth_access_tokens')->where(['user_id' => $userId, 'revoked' => false ])->first();

        return $scope;
    }

    function getTicketCategory($id) {

        $scope = Category::find($id);

        return $scope;
    }

    function getTicketCategories($cond) {

        $cat = Category::query()->where($cond)->get();

        return $cat;
    }

    function insertTicket($ticket) {

        $insert = DB::table('ticket.ticket')->insertGetId((array) $ticket);

        return $insert;
    }

    function updateTicket($where, $update) {

        $update = Ticket::query()->where($where)->update($update);

        return $update;
    }

    function getTicketComments($cond,$paginate) {

        $ticket = TicketComment::where($cond)->with('ticket', 'ticket.category', 'ticket.categorySub')->with('user', 'admin')->simplePaginate($paginate);

        return $ticket;
    }

    function insertComment($comment) {

        $insert = DB::table('ticket.ticket_comment')->insertGetId((array) $comment);

        return $insert;
    }

}
