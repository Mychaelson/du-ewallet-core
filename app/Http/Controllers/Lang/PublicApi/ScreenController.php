<?php

namespace App\Http\Controllers\Lang\PublicApi;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Lang\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\Lang\PublicApi\Screen\Resource as ResultResource;
use App\Resources\Lang\PublicApi\Screen\Collection as Resultcollection;
use App\Repositories\Lang\ProjectScreenRepository;
use DB;

class ScreenController extends Controller
{
    protected $request;
    private $projectScreenRepository;

    public function __construct(
        Request $request,
        ProjectScreenRepository $projectScreenRepository,
    ) {
        $this->request = $request;
        $this->projectScreenRepository = $projectScreenRepository;
    }

   public function actionGetScreen($projectId)
   {
    
       $sanitized = $this->validate($this->request, [
           'version' => 'exists:App\Models\Lang\ProjectVersion,project_version'
       ]);
       $data = $this->projectScreenRepository->actionGetScreen($projectId, $sanitized);

       return new Resultcollection($data); 

   }

   public function actionViewScreen($projectId,$screenId)
   {
        $sanitized = $this->validate($this->request, [
            'version' => 'exists:App\Models\Lang\ProjectVersion,project_version',
            'hl' => 'exists:App\Models\Lang\ProjectLanguage,hl'
        ]);
        
        $data = $this->projectScreenRepository->actionViewScreen($projectId, $screenId, $sanitized);

        return response()->json([
            'success' => TRUE,
            'description' => "OK",
            'response_code' => 200,
            'data' => !$data ? [] : new ResultResource($data)
        ], 200);
   }
}
