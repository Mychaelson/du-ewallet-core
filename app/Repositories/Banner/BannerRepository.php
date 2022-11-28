<?php

namespace App\Repositories\Banner;


use Illuminate\Support\Facades\DB;
use App\Models\Banner\Banner;

class BannerRepository
{

    public function GetBanner($request)
    {
        $date = date('Y-m-d H:i:s');
        // biar ada datanya
        $data = Banner::whereRaw("status = '2'");
        
        /*
        <-- pengecekan aslinya 
        noted:  time_end dan time_start untuk pengecekan banner masih aktif atau tidak   -->
        */
        // $data = Banner::whereRaw("status = '2' and time_end  >= '$date' and time_start  <= '$date' ");
        if(isset($request['label'])){
            $data = $data->where('label',$request['label']);
        }elseif(isset($request['group'])){
            $data = $data->where('group',$request['group']);
        }

        $data = $data->orderBy('id','DESC')->get();

        return $data;

    }

    public function GetBannerId($id)
    {
        
        $data = Banner::whereRaw("status = '2'")->where('id',$id)->first();

        return $data;
    }
    

}
