<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\UserContactsRepository;

class UserContactsController extends Controller
{
    public function __construct
    (
        private UserContactsRepository $ucRepository
    )
    {}

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        $data['user'] = auth('api')->user();

        //validate fields
        $data = $this->validateFields($data);
        if ( !$data['response']['success'] )
            return response($data['response'])->header('Content-Type', 'application/json');

        //execute
        $data = $this->$action($data);
        
        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function add($data)
    {
        $content = reset($data['request']['content']);
        $user = $data['user'];

        foreach ($content as $key => $contact) {
            $phone = generate_username($contact['phone'], $user->phone_code);
            $validateContact = $this->ucRepository->getUserContactsByUserIdAndPhone($user->id, $phone);
            if (is_null($validateContact)) {
                //should insert
                $newContact = array(
                    'user_id' => $user->id,
                    'name' => $contact['name'],
                    'phone' => $phone,
                    'meta' => json_encode($contact['meta']),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->ucRepository->create($newContact);
            } else {
                $updates = array(
                    'name' => $contact['name'],
                    'phone' => $phone,
                    'meta' => json_encode($contact['meta']),
                );
                $this->ucRepository->update($validateContact->id, $updates);
            }
        }
        $data['response']['message'] = trans('messages.contacts-added');
        return $data;
    }
    
    private function validateFields($data)
    {
        $content = $data['request']['content'];
        $skipValidation = [''];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } else if ($data['action'] == 'add') {
    		$rules = array(
	    		'contacts' => 'present|array',
                'contacts.*.name' => 'required',
                'contacts.*.phone' => 'required|numeric',
                'contacts.*.meta' => 'required',
	    	);
    	}

        $validator = Validator::make($content, $rules);
        if($validator->fails()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            foreach ($validator->errors()->messages() as $field => $value) {
                foreach ($value as $key => $message) {
                    $data['response']['message'] .= "$message ";
                }
            }
        }

        return $data;
    }
}