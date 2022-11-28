<?php

namespace App\Http\Controllers\Ppob;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Cellular\CellularRepository;
use App\Repositories\Ppob\Pdam\PdamRepository;
use App\Repositories\Ppob\Games\GamesRepository;
use App\Repositories\Ppob\Base\PpobRepository;
use App\Repositories\Ppob\DigitalProductsRepository;
use App\Repositories\Ppob\DigitalCategoryRepository;
use App\Models\Ppob\DigitalCategories;
use App\Resources\Ppob\CategoryProduct\CategoryProductResource as ResultResource;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;

class ProductController extends Controller
{
    protected $currency;
    protected $cellular;
    protected $pdam;
    protected $games;
    protected $base;
    private $digitalProductsRepository;
    private $digitalCategories;

    public function __construct(
        CellularRepository $cellular ,
        PdamRepository $pdam,
        GamesRepository $games,
        PpobRepository $base,
        DigitalProductsRepository $digitalProductsRepository,
        Request $request, 
        DigitalCategories $digitalCategories
        )
    {

        $this->cellular = $cellular;
        $this->pdam = $pdam;
        $this->games = $games;
        $this->base = $base;
        $this->digitalCategories = $digitalCategories;
        $this->currency = request()->input('currency', 'IDR');
        $this->userId = isset($request->user()->id) ? $request->user()->id : 26;
    }
    

    public function pulsa(Request $request)
    {
        if(! $request->has('phone') || !isset($request->phone))
            return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.input_phone_number_first')], 404);

        \Log::info('product controller, valid phone :: getRequest '.json_encode($request->all()));
            
        $phone = valid_phone($request->phone);
        $cat = $this->phonePrefix($phone);
        $currency = 'IDR';
        $ip = $request->ip();

        if(!$cat)
            return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.data_not_found')], 404);

            
        $data = $this->cellular->getDataV2($cat,$currency,$ip);

        return $data;
    }

    public function gameCategory(Request $request)
    {
        $data = $this->base->getList('games',$request);

        return $data;
    }

    public function pdam(Request $request)
    {
        $data = $this->pdam->getList($request);

        return $data;
    }

    public function evoucherCategory(Request $request)
    {
        $data = $this->base->getList('e-voucher',$request);

        return $data;

    }

    private function phonePrefix($phone)
    {   
        $prefix = config('config.phone_prefix');
        $phonePrefix = collect($prefix); 
        $key = false;
        foreach($prefix as $k => $v) {
            if(in_array(substr($phone, 0, 4), $v)){
                $key = $k;
                break;
            }
        }
        return $key;
    }
    
    public function products($slug ,Request $request)
    {
        //$currency = isset($request->currency) ? $request->currency : "IDR";
        $category = $this->digitalCategories->with([
            'products' => function($q) { 
                //$q->where('currency', $currency)->where('status', 1)->orderBy('order'); 
                $q->where('status', 1)->orderBy('name'); 
            }
        ])->where(function ($query) use ($slug) {
            if((int) $slug != 0){
                $query->where('id', $slug);
            }else{
                $query->where('slug', $slug);
            }
        })->first();
        
        if($category){
            return response()->json([
                'success'=> true,
                'response_code' => 200,
                'data' => new ResultResource($category)
            ], 200);
        }

        return response()->json([
            'success'=>false,
            'response_code' => 404,
            'message' => trans('error.data_not_found')
        ], 404);
    }

    public function productCategory($slug, Request $request)
    {
        //$currency = isset($request->currency) ? $request->currency : "IDR";
        $category = $this->digitalCategories->where('slug', $slug);
        $data = $category->with(['products' => function($q){ 
            $q->where('status', 1)->orderBy('name');
        }])->get();

        if($category->count() > 0){
            return (new Resultcollection($data))
            ->response()
            ->header('X-Ip-Request', $request->ip());
        }

        return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.data_not_found')], 404);
    }
    
    
      public function show($id)
      {
          $where = ['id' => $id];
          $row = $this->digitalProductsRepository->first($where);
    
          if($row)
            return $this->response($this->productResource($row));
    
          return $this->response(null, 404, 'data not found');
      }
    
      public function response($data, $code = 200, $message = null)
      {
        $res = ['success' => $code == 200,
                'response_code' => $code,
                'data' => $data ?? [],
                'message' => $message];
    
        return response()->json($res, $code);
      }
    
      public function productResource($data)
      {
        $data->map(function ($q) {
    
          $meta = [];
          if(is_array($q->meta)){
              $meta = array_map(function($v){
                  if($v === 'true' || $v === 'false'){
                      return (boolean)$v;
                  }else {
                      return (string)$v;
                  }
              }, $q->meta);
          }
    
          $q['meta'] = (object) $meta;
    
          return $q;
        });
        return $data;
    }

    public function updateProductService(Request $request){
        $result = $this->base->updateProduct();

        $data['response']['success'] = true;
        $data['response']['response_code'] = 200;
        $data['response']['message'] = 'Data has been update';
        $data['response']['data'] = '';

        return response()->json($data);
    }
}
