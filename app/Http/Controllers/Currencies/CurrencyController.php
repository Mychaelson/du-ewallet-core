<?php

namespace App\Http\Controllers\Currencies;

use Illuminate\Http\Request;
use \DB;

use App\Http\Controllers\Controller;
use App\Repositories\Currencies\CurrencyRepository;

class CurrencyController extends Controller
{
    private $currencyRepository;

    public function __construct(
        CurrencyRepository $currencyRepository,
    )
    {
    	$this->currencyRepository = $currencyRepository;
    }

    public function getActivationPurposes(Request $request)
    {
        $status = false;
        $resp_code = 404;
        $message = "";
        $data = array();

        $userId = auth()->id();


        $user = isset($userId) ? $userId : 0;
        if(empty($user) && !empty($request->query("user"))) {
            $user = $request->query("user");
        }

        //get activation purpose data
        $purpose = $this->currencyRepository->activation_purposes();

        if(!empty($purpose)){
            $data = $purpose;
            $status = true;
            $resp_code = 200;
        }else{
            $message = "data purpose not found";
        }
        $responses = array(
              "success" => $status,
              "response_code" => $resp_code,
              "message" => $message,
              "data" => $data
        );

        return response()->make($responses, '200');
    }

    public function getUserPurpose(Request $request)
    {

        $userId = auth()->id();

        $resp_code = 404;
        $status = false;
        $message = "";
        $data = array();

        $user = isset($userId) ? $userId : 0;
        if(empty($user) && !empty($request->query("user"))){
            $user = $request->query("user");
        }

        $purpose = $this->currencyRepository->user_purpose($user);
        if(!empty($purpose)){
            $resp_code = 200;
            $status = true;
            $data = $purpose;
        }else{
            $message = "data user purpose not found";
        } 

        $responses = array(
              "success" => $status,
              "response_code" => $resp_code,
              "message" => $message,
              "data" => $data
        );

        return response()->make($responses, '200');
    }

    public function saveUserPurpose(Request $request)
    {
        $resp_code = 404;
        $status = false;
        $message = "";
        $data = array();

        
        $userId = auth()->id();

        $purpose = $request->purpose;
        $user = isset($userId) ? $userId : 0;
        if(empty($user) && !empty($request->query("user"))){
            $user = $request->query("user");
        }
  
        if(!empty($user) && !empty($purpose)){
          $psql = $this->currencyRepository->detail_purposes($purpose);
          if(!empty($psql)){
            $s_mereg = $this->currencyRepository->user_purpose($user);
            $data_upd['purpose_id'] = $purpose;
            if(!empty($s_mereg)){
                $curr = $this->currencyRepository->updu_purpose($data_upd, array("id"=>$s_mereg->id));
            }else{
                $data_upd['user_id'] = $user;
                $curr = $this->currencyRepository->insu_purpose($data_upd);
            }
  
            if($curr){
                $status = true;
                $resp_code = 200;
                $data = $psql;
            }else $message = "failed to save purpose data";
          }else $message = "data purpose not found"; /* $message = "region requests are required to get the public price"; */
        }else $message = "data purpose not found"; /* $message = "region requests are required to get the public price"; */
  
        $responses = array(
              "success" => $status,
              "response_code" => $resp_code,
              "message" => $message,
              "data" => $data
        );

        return response()->make($responses, '200');
    }

    public function registerUserRegion(Request $request){

        $userId = auth()->id();

        $user = isset($userId) ? $userId : 0;

        $country = $request->country;
        $provinces = $request->provinces;
        $region_name = (!empty($provinces)) ? $provinces : $country;
        $type = (!empty($request->provinces)) ? "provinces" : "country";
        $message = "";
        $data = array();

        $status=false;
        $resp_code = 404;
        $data=array();

        if(!empty($user)){
            $cek_mer = $this->currencyRepository->check_user($user);
            if(!empty($cek_mer)){
                      $regn = $this->currencyRepository->getRegionByCode($region_name, $type);
                      $region = (!empty($regn)) ? $regn->id : 0;
                      $arr_insert = array("user_id" => $user, "region_id" => $region, "region_type"=>$type);
                      $s_region = $this->currencyRepository->detailRegion($region, $type);
                      if(!empty($s_region)){
                          $ins = $this->currencyRepository->registerUser($arr_insert, $user);
                          if($ins){
                              $status = true;
                              $resp_code = 200;
                              $data = array("region"=>array("id"=>$regn->id, "name"=>ucwords(strtolower($regn->nicename))));
                              $message = "save user region success";
                          }else $message = "something went wrong when saving user region";
                      }else $message = "region not registered";
            }else $message = "user not registered";
        }else{
            $resp_code = $resp_code;
            $message = "unauthorized";
        }

        $responses = array(
            "success"=>$status,
            "response_code"=>$resp_code,
            "message"=>$message,
            "data"=>$data
        );

        return response()->make($responses, '200');
    }


}
