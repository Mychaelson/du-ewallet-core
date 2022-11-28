<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function __construct(
        private UsersRepository $usersRepository
    ) {
    }

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        $data['ip'] = $request->ip();
        $data['device'] = $request->header('X-Device-Name');
        $data['device_id'] = $request->header('X-Device-ID');
        $location = ip2location($data['ip']);
        $data['location'] = $location->fullAddress();

        //validate fields
        /*$data = $this->validateFields($data);
        if (! $data['response']['success'])
            return response($data['response'])->header('Content-Type', 'application/json');*/

        //execute
        $data = $this->$action($data);

        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function redirect($data)
    {
        $data['response']['data'] = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return $data;
    }

    private function auth($data)
    {
        $data['response']['data'] = Socialite::driver('google')->stateless()->user();
        \Log::debug(json_encode($data));

        return $data;
    }

    private function validateFields($data)
    {
        $skipValidation = ['auth', 'redirect'];
        $content = $data['request']['content'];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        }

        $validator = Validator::make($content, $rules);
        if ($validator->fails()) {
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
