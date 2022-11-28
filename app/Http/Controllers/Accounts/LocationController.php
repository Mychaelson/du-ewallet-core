<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\CountriesRepository;

class LocationController extends Controller
{
    public function __construct
    (
        private CountriesRepository $countriesRepository
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
        $locations =  null;
        
        if ($content['type'] == 'country')
            $locations = $this->countriesRepository->getCountriesByName();

        $data['response']['data'] = $locations;
        return $data;
    }
    
    private function validateFields($data)
    {
        $content = $data['request']['content'];
        $skipValidation = [];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } else if ($data['action'] == 'lists') {
    		$rules = array(
	    		'type' => 'required',
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