<?php

namespace App\Http\Controllers\Ticket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Ticket;
use App\Repositories\Ticket\TicketRepository;
use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{
    
    private $ticketRepository;

	function __construct(
        TicketRepository $ticketRepository,
    )
	{
        $this->ticketRepository = $ticketRepository;
	}

    public function getTickets(Request $request)
    {
        $limit = isset($request->rpp) ? $request->rpp : 12;
        $page  = isset($request->page) ? $request->page : 1;

        $offset = $page > 0 ? (($page - 1) * $limit) : 0;

        $query = $request->query();
        $cond = [];
        $response = [];

        $userId = auth()->id();


        $cond = [
            'user' => $userId,
            ['status', '>', '0'],
        ];

        // if(isset($query)){
        //     foreach($query as $key => $check){
        //         $cond[$key] = $check;
        //     }
        // }


        unset($cond['/api/ticket']);
        $get_data = $this->ticketRepository->getTickets($cond, $limit);

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['total'] = $limit;
        $response['rpp'] = $limit;
        $response['page'] = $page;
        $response['data'] = $get_data->items();
        
        return response()->json($response,200);

    }

    public function createTicket(Request $request)
    {

        $is_service = false;
        $userId = auth()->id();

        if (!Auth::check()) {
            $is_service = true;
            
            $checkScopes = $this->ticketRepository->checkScope($userId);
            $scopes = json_decode($checkScopes->scopes);
            if(!in_array('ticket_create', $scopes)){
                $response['success'] = false;
                $response['response_code'] = 401;
                $response['message'] = 'Unauthorized';


                return response()->json($response,401);
            }
        }

        $ticket = (object)[
            'subject'      => $request->subject,
            'category'     => $request->category,
            'category_sub' => $request->category_sub,
            'body'         => $request->body,
            'attachment'   => $request->attachment,
            'reff'         => $request->reff,
            'service'      => $request->service,
            'user'         => $request->user,
        ];


        if(!$is_service){
            $ticket->user = $userId;
            unset($ticket->service);
        }

        $errors = [];

        // .subject
        if(!$ticket->subject)
        $errors['subject'] = 'this field is required';
    
        $cat = null;
        $subcat = null;

        // .category
        if(!$ticket->category)
            $errors['category'] = 'this field is required';
        else{
            $cat = $this->ticketRepository->getTicketCategory($ticket->category);
            if(!$cat)
                $errors['category'] = 'category not found';
        }
    
  

        // .category_sub
        if(!$ticket->category_sub)
            $errors['category_sub'] = 'this field is required';
        else{
            $subcat = $this->ticketRepository->getTicketCategory($ticket->category_sub);

            if(!$subcat)
                $errors['category_sub'] = 'selected sub category is not found';
            elseif($subcat->parent != $ticket->category)
                $errors['category_sub'] = 'selected sub category is not a child of parent category';
        }


        if($is_service){
            // .service
            if(!$ticket->service)
                $errors['service'] = 'this field is required';

            // .user
            if(!$ticket->user)
                $errors['user'] = 'this field is required';
        }

        if($errors){
            $errors['success'] = false;
            $errors['response_code'] = 422;


            return $this->resp($errors, 422);
        }

        // all validation okay
        $ticket->scope      = $subcat->scope;
        $ticket->tts        = $subcat->tts;
        $ticket->priority   = $subcat->priority;
        $ticket->status     = 2;
        $ticket->created_at = now();
        $ticket->updated_at = now();

        //Insert and get ID
        $insertId = $this->ticketRepository->insertTicket($ticket);
        if(!$insertId){
            $response['success'] = false;
            $response['response_code'] = 500;
            $response['message'] = 'Insert Error';


            return response()->json($response,500);
        }

        $cond = [];
        $cond['id'] = $insertId;
        $limit = 1;

        $get_data = $this->ticketRepository->getOneTicket($cond, $limit);

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $get_data;
        
        return response()->json($response,200);

    }

    public function getOneTicket($id)
    {

        $userId = auth()->id();


        $cond = [
            'id' => $id,
            'user' => $userId,
            ['status', '>', '0'],
        ];

        $get_data = $this->ticketRepository->getOneTicket($cond);

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $get_data;
        
        return response()->json($response,200);

    }

    public function getTicketCategories(Request $request)
    {

        $parent = isset($request->parent) ? $request->parent : 0;

        $userId = auth()->id();


        $cond = [
            'parent' => $parent,
            'status' => 1,
        ];

        $get_data = $this->ticketRepository->getTicketCategories($cond);

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $get_data;
        $response['total'] = count($get_data);

        return response()->json($response,200);

    }

    public function getTicketCategory($id)
    {

        $userId = auth()->id();


        $get_data = $this->ticketRepository->getTicketCategory($id);

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $get_data;
        
        return response()->json($response,200);

    }

    public function getCommentTicket(Request $request,$id)
    {

        $limit = isset($request->rpp) ? $request->rpp : 12;
        $page  = isset($request->page) ? $request->page : 1;

        $userId = auth()->id();

        $cond = [
            'id' => $id,
            'user' => $userId,
            ['status', '>', '0'],
        ];

        //get and check if the ticket exist
        $ticket = $this->ticketRepository->getOneTicket($cond);
        if(!$ticket){
            $response['success'] = false;
            $response['response_code'] = 404;
            $response['message'] = 'Ticket not found';

            return response()->json($response,404);

        }

        $condComment = [
            'ticket' => $ticket->id
        ];

        $get_data = $this->ticketRepository->getTicketComments($condComment,$limit);

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $get_data->items();
        $response['total'] = $get_data->count();
        $response['rpp'] = $limit;
        $response['page'] = $page;

        return response()->json($response,200);

    }

    public function createComment(Request $request,$id)
    {
        $userId = auth()->id();

        $cond = [
            'id' => $id,
            'user' => $userId,
            ['status', '>', '0'],
        ];

        //get and check if the ticket exist
        $ticket = $this->ticketRepository->getOneTicket($cond);
        if(!$ticket){
            $response['success'] = false;
            $response['response_code'] = 404;
            $response['message'] = 'Ticket not found';

            return response()->json($response,404);

        }


        $comment = (object)[
            'ticket'        => $ticket->id,
            'body'          => $request->body,
            'attachment'    => $request->attachment,
            'user'          => $userId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        $errors = [];

        // Body validation
        if(!$comment->body)
        $errors['body'] = 'this field is required';
    
        if($errors){
            $errors['success'] = false;
            $errors['response_code'] = 422;


            return $this->resp($errors, 422);
        }


        //Insert and get ID
        $insertId = $this->ticketRepository->insertComment($comment);
        if(!$insertId){
            $response['success'] = false;
            $response['response_code'] = 500;
            $response['message'] = 'Insert Error';


            return response()->json($response,500);
        }

        $cond = [];
        $cond['id'] = $insertId;
        $limit = 1;

        $get_data = $this->ticketRepository->getTicketComments($cond, $limit);

        // reset ticket status to 3 if it's not 3 and 2
        if(!in_array($ticket->status, ['2','3'])){
            $update = $this->ticketRepository->updateTicket(['id' => $ticket->id], ['status' => 3]);
        }

        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $get_data->items();


        return response()->json($response,200);

    }

    public function getCustomerCare(){
        $data = array('Ticket Saya', 'Pusat Bantuan');
        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $data;

        return response()->json($response,200);
    }
}
