<?php

namespace App\Http\Controllers\Docs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Docs\Document;
use App\Models\Docs\Help;
use App\Models\Docs\HelpCategory;
use App\Resources\Docs\Document\Resource as ResultResource;
use App\Resources\Docs\Document\Collection as Resultcollection;
use App\Repositories\Docs\DocumentRepository;



class DocsController extends Controller
{
    private $documentRepository;

    public function __construct(
        DocumentRepository $documentRepository,
    ) {
        $this->documentRepository = $documentRepository;
    }

   public function indexDocs(Request $request)
   {
       
        $data = $this->documentRepository->GetDocs($request->all());

        return new Resultcollection($data);
        
   }

   public function singleActionDocs(Request $request ,$slug)
   {

        $data = $this->documentRepository->GetDocsSlug($request->all(),$slug);
            
        return response()->json([
            'success' => TRUE,
            'response_code' => 200,
            'data' => !$data ?[] : new ResultResource($data),
        ], 200);
   }

}
