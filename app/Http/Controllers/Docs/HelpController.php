<?php

namespace App\Http\Controllers\Docs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Resources\Docs\Help\Resource as ResultResource;
use App\Resources\Docs\Help\Collection as Resultcollection;
use App\Resources\Docs\HelpCategory\Resource as ResultResourceC;
use App\Repositories\Docs\HelpRepository;


class HelpController extends Controller
{
    private $helpRepository;

    public function __construct(
        HelpRepository $helpRepository,
    ) {
        $this->helpRepository = $helpRepository;
    }

    public function singleActionHCategory(Request $request ,$slug,$helpId){
        
        $data = $this->helpRepository->GetHelpSlugId($request->all() ,$slug,$helpId);
        
        return response()->json([
            'success' => TRUE,
            'response_code' => 200,
            'data' => !$data ? [] : new ResultResource($data)
        ], 200);
    }

    public function searchHelp(Request $request){

        $data = $this->helpRepository->searchHelp($request->all());
        
        return new Resultcollection($data);
    }

    public function searchHelpbySlug(Request $request,$slug){
        
        $data = $this->helpRepository->SearchHelpSlug($request->all(),$slug);
        return new ResultResourceC($data);
    }

}
