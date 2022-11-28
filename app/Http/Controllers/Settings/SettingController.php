<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Repositories\RedisRepository;
use DB;

class SettingController extends Controller
{
    private $redisRepository;

    public function __construct
    (
        RedisRepository $redisRepository,
    )
    {
    	$this->redisRepository = $redisRepository;
    }


    public function index()
    {
        //check from cache first
        $settingcache = $this->redisRepository->get("settings-siteParam");
        if(!is_null($settingcache)){
            $response['success'] = true;
            $response['response_code'] = 200;
            $response['data'] = json_decode($settingcache, true);

            return response()->json($response,200);
        }

        $setting = DB::table('setting.site_params')->select('name', 'type', 'group', 'value')->get();


        $response['success'] = true;
        $response['response_code'] = 200;
        $response['data'] = $setting;

        //cache result for 1 hour
        $this->redisRepository->setNxPx("settings-siteParam", json_encode($setting), 3600000);

        return response()->json($response,200);

    }

}
