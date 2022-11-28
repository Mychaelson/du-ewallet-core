<?php

namespace App\Http\Controllers\Docs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Resources\Docs\HelpCategory\Resource as ResultResource;
use App\Resources\Docs\HelpCategory\Collection as Resultcollection;
use App\Resources\Docs\Help\Collection as ResultcollectionH;
use App\Repositories\Docs\HelpCategoryRepository;
use App\Repositories\Docs\HelpRepository;


class HelpCategoryController extends Controller
{
    private $helpCategoryRepository;

    public function __construct(
        HelpCategoryRepository $helpCategoryRepository,private HelpRepository $helpRepository
    ) {
        $this->helpCategoryRepository = $helpCategoryRepository;
    }

   public function singleAction(Request $request ,$id){

        $data = $this->helpCategoryRepository->GetHCategorySlug($request->all,$id)->first();
        $category = $this->helpRepository->SearchHelpByCategori($data->id);
        if($request->q){
            $q = $request->q;
            $category = $this->helpRepository->SearchHelpByCategoriQ($q,$data->id);

        }
        // $datas = DB::table('docs.help')->where(['category'=>$data->id])->get();

        return new ResultcollectionH($category);

    }

    public function indexAction(Request $request){
       
        $data   = $this->helpCategoryRepository->GetHCategory($request->all());

        return new Resultcollection($data);

    }

}
