<?php

namespace App\Http\Controllers\Banner;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Banner\Banner;
use DB;
use App\Resources\Banner\Resource as ResultResource;
use App\Resources\Banner\Collection as Resultcollection;
use App\Repositories\Banner\BannerRepository;

class BannerController extends Controller
{
    private $bannerRepository;

    public function __construct(
        BannerRepository $bannerRepository,
    ) {
        $this->bannerRepository = $bannerRepository;
    }

   public function indexAction(Request $request)
   {
        $data = $this->bannerRepository->GetBanner($request->all());

        foreach($data as &$banner){
            unset($banner->web);
            unset($banner->phone);
            unset($banner->email);
            unset($banner->cover);
            unset($banner->highlight);
            unset($banner->terms);
        }
        unset($banner);

        return new Resultcollection($data);
        
   }

   public function singleAction($id)
   {

       $data = $this->bannerRepository->GetBannerId($id);
       
        return response()->json([
            'success' => TRUE,
            'response_code' => 200,
            'data' => !$data ? [] : new ResultResource($data),
        ], 200);
   }
}
