<?php

namespace App\Repositories\Setting;


use Illuminate\Support\Facades\DB;


class SiteParamsRepository
{

    function getFirst($where) {

        $site = DB::table('setting.site_params')->where($where)->first();

        return $site;
    }

}
