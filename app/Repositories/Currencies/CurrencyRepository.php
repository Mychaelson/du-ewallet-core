<?php

namespace App\Repositories\Currencies;


use Illuminate\Support\Facades\DB;

use Firebase\JWT\JWT;

class CurrencyRepository
{

    public $status  = 'status';
    public $error   = 'error';
    public $user_data;
    public $sess;

    function activation_purposes() {
        $query = DB::table('currencies.activation_purpose')->select('id', 'purposes')->get();

        return $query;
    }

    function user_purpose($user) {
        $query = DB::table('currencies.user_purpose')
                ->join('currencies.activation_purpose', 'currencies.activation_purpose.id', '=', 'currencies.user_purpose.purpose_id')
                ->select('currencies.user_purpose.id as id', 'currencies.activation_purpose.purposes as purpose','currencies.user_purpose.user_id as user')
                ->where('user_id', $user)
                ->first();

        return $query;
    }

    function detail_purposes($id) {
        $query = DB::table('currencies.activation_purpose')->select('id', 'purposes')->where('id', $id)->first();

        return $query;
    }

    function updu_purpose($data, $cond) {
        $query = DB::table('currencies.user_purpose')->where($cond)->update($data);

        return $query;
    }

    function insu_purpose($data) {
        $query = DB::table('currencies.user_purpose')->insert($data);

        return $query;
    }

    function check_user($user) {

        $query = DB::table('accounts.users')->selectRaw("id, username, nickname, name, IFNULL(main_device, '') as main_device")->where('id', $user)->first();

        return $query;
    }

    function getRegionByCode($iso='', $region_type="country"){
        if($region_type == "country"){
            $rsql = DB::table('accounts.countries');
            
            if(strlen($iso) <= 2){
                $rsql = $rsql->where('iso', strtoupper($iso));
            }elseif(strlen($iso) == 3){
                $rsql = $rsql->where('iso3', strtolower($iso));
            }else{
                $rsql = $rsql->where('curr_index', 'ilike', "%$iso%");
            }

            $rqsl = $rsql->first();

        }else{
            $rsql = DB::table('accounts.provinces')->where('id',$iso)->first();
        }

        $recond = ($rsql) ? $rsql : array();
        return $recond;
    }

    function detailRegion($region_id=0, $region_type="country"){
        if($region_type == "country"){
            $rsql = DB::table('accounts.countries')->where('id', $region_id)->first();
        }else{
            $rsql = DB::table('accounts.provinces')->where('id', $region_id)->first();
        }

        $recond = ($rsql) ? $rsql : array();
        return $recond;
    }

    function dataUserRegion($region_id=0, $region_type="country"){
        $rsql = DB::table('currencies.user_region')->where(['region_id' => $region_id, 'region_type' => $region_type])->first();
        
        $recond = ($rsql) ? $rsql : array();
        return $recond;
    }

    function registerUser($data, $id){
        $cur = DB::table('currencies.user_region')->where('user_id', $id)->first();
        if($cur){

            $query = DB::table('currencies.user_region')->where('user_id', $id)->update($data);

        }else{

            $query = DB::table('currencies.user_region')->insert($data);

        }
        
        return $query;
    }
}
