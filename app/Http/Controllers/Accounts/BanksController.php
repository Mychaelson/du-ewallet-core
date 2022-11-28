<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\BanksRepository;
use App\Repositories\Payment\PgaBankAccountRepository;

class BanksController extends Controller
{
    public function __construct
    (
        private BanksRepository $banksRepository,
        private PgaBankAccountRepository $pgaBankAccountRepository
    )
    {}

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);

        //validate fields
        $data = $this->validateFields($data);
        if ( !$data['response']['success'] )
            return response($data['response'])->header('Content-Type', 'application/json');

        //execute
        $data = $this->$action($data);
        
        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function lists($data)
    {
        $content = $data['request']['content'];
        $data['response']['data'] =  $this->banksRepository->getBanksByBankName($content['name']);
        return $data;
    }

    private function company($data)
    {
        $content = $data['request']['content'];
        $data['response']['data'] =  $this->banksRepository->getBankCompany();
        return $data;
    }

    private function check($data)
    {
        $content = $data['request']['content'];
        $checkPga = $this->pgaBankAccountRepository->chekBankAccount($content);

        if($checkPga['status'] != '000'){
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = $checkPga['error_message'];
        }else{
            $data['response']['success'] = true;
            $data['response']['data'] = $checkPga['data'];
        }
        
        return $data;
    }
    
    private function validateFields($data)
    {
        $content = $data['request']['content'];
        $skipValidation = ['company'];

        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } else if ($data['action'] == 'lists') {
    		$rules = array(
	    		'name' => 'required',
	    	);
    	} else if ($data['action'] == 'check') {
    		$rules = array(
	    		'number' => 'required',
                'bank' => 'required',
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