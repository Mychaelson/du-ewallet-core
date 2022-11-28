<?php

namespace App\Http\Controllers\Lang\PublicApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lang\Project;
use App\Resources\Lang\PublicApi\Project\Resource as ResultResource;
use App\Resources\Lang\PublicApi\Project\Collection as Resultcollection;
use App\Repositories\Lang\ProjectRepository;
use DB;


class ProjectController extends Controller
{

    private $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository,
    ) {
        $this->projectRepository = $projectRepository;
    }

    public function actionGetProject(Request $request)
   {
      
      $data = $this->projectRepository->actionGetProjects();
      return new Resultcollection($data);
        
   }

   public function actionViewProject($id)
   {

      $data = $this->projectRepository->actionViewProject($id);

      return response()->json([
        'success' => TRUE,
        'description' => "OK",
        'response_code' => 200,
        'data' => !$data ? [] : new ResultResource($data)
    ], 200);
    
   }
}
