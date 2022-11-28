<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\RedisRepository;
use App\Repositories\Accounts\UserInformationsRepository;
use App\Repositories\Accounts\UsersRepository;

use App\Macros\Accounts\UserInformationMacro;

use App\Notifications\Accounts\KycNotification;

class UserInformationController extends Controller
{
    public function __construct
    (
        private RedisRepository $redisRepository,
        private UserInformationsRepository $uiRepository,
        private UserInformationMacro $uiMacro,
        private KycNotification $kycNotification,
        private UsersRepository $usersRepository
    )
    {}

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        if(isset($data['request']['content']['q'])){
            $data['user'] = $this->usersRepository->getUserSearch($data['request']['content']['q']);
        }else{
            $data['user'] = auth('api')->user();
        }
        //validate field
        $data = $this->validateFields($data);
        if ( !$data['response']['success'] )
            return response($data['response'])->header('Content-Type', 'application/json');

        //execute
        $data = $this->$action($data);

        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function information($data)
    {
        //should execute user information macro
        $data = $this->uiMacro->handle($data, true);
        return $data;
    }

    private function update($data)
    {
        $content = $data['request']['content'];

        //validate and create if not exist
        $validate = $this->uiRepository->getUserInformationByUserId($data['user']->id);
        if (is_null($validate))
            $this->uiRepository->create($data['user']->id);

        //update data
        $this->uiRepository->update($content, $data['user']->id);
        
        //execute user information macro
        $info = $this->uiMacro->handle($data, true);

        //kyc notification
        if (isset($content['active'])) {
            $this->kycNotification->handle([
                'email' => $data['user']->email,
                'active' => $content['active'], 
                'username' => $data['user']->name ?? $data['user']->username]
            );
        }

        $data['response']['message'] = trans('messages.user-updated');
        $data['response']['data'] = $info['response']['data'];
        return $data;
    }


    private function validateFields($data)
    {
        $skipValidation = ['information'];
        $content = $data['request']['content'];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } elseif ($data['action'] == 'update') {
            $acceptedField = [
                'mother_name', 'identity_type', 'identity_number', 
                'identity_image', 'identity_expired', 'identity_source',
                'identity_status', 'identity_note', 'photo', 
                'photo_status', 'photo_note', 'npwp_number', 
                'npwp_image', 'npwp_valid', 'npwp_invalid_reason', 
                'kyc_image', 'passport_number', 'passport_image', 
                'identity_number_of_family', 'nationality', 'is_valid', 
                'status', 'active', 'approved_by'
            ];

            foreach ($content as $key => $value) {
                if (!in_array($key, $acceptedField)) {
                    $data['response']['success'] = false;
                    $data['response']['response_code'] = 422;
                    $data['response']['message'] = trans('messages.invalid-field', ['field' => $key]);
                    return $data;
                }

                $rules[$key] = 'required';
            }
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

            return $data;
        }

        return $data;
    }

    public function registerDevice(Request $request){
        // set validation
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'device_type' => 'required',
            'main_device_name' => 'required',
        ]);

        // response error validation
        if ($validator->fails()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_BAD_REQUEST;
            $data['response']['message'] = $validator->errors()->first();
            $data['response']['data'] = $validator->errors();

            return response()->json($data['response'], $data['response']['response_code']);
        }
        $user = $request->user();

        $insert['device_token'] = $request->input('device_token');
        $insert['device_name'] = $request->input('main_device_name');
        $insert['user_id'] = $user->id;
        $insert['location'] = $user->token()->location;
        $insert['login_count'] = 1;
        
        $result = $this->usersRepository->registerDevice($insert);

        $response['response']['message'] = 'Register device success';
        $response['response']['response_code'] = 200;
        $response['response']['success'] = true;
        $response['response']['data'] = $result;

        return Response($response['response'])->header('Content-Type', 'application/json'); 
    }
}