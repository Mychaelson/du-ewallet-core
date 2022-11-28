<?php

namespace App\Repositories\Lang;


use Illuminate\Support\Facades\DB;
use App\Models\Lang\Project;
use App\Models\Lang\ProjectVersion;
use App\Models\Lang\ProjectLanguage;
use App\Models\Lang\ProjectScreenTranslationList;
use App\Models\Lang\ProjectScreen;
use App\Repositories\Lang\ProjectRepository;

class ProjectScreenRepository
{


    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    private $projectRepository;

    public function __construct(
        ProjectRepository $projectRepository,
    ) {
        $this->projectRepository = $projectRepository;
    }


    public function actionGetScreen($projectId, $filter)
    {
        $version = 0;
        if(isset($filter['version'])) {
            if($versionCheck = ProjectVersion::where('project_version', $filter['version'])->where('project_id')->first() ){
                $version = $versionCheck->id;
            }
        }
        if($version==0){
            $version = $this->projectRepository->latestVersionId($projectId);
        }
        return ProjectScreen::where('status', self::STATUS_ACTIVE)->where('project_version_id', $version)->paginate();
    }

    public function actionViewScreen($projectId, $screenId, $filter)
    {   
        $hl = null;
        $version = 0;

        if(isset($filter['version'])) {
            if($versionCheck = ProjectVersion::where('project_version', $filter['version'])->where('project_id')->first() ){
                $version = $versionCheck->id;
            }
        }
        if($version==0){
            $version = $this->projectRepository->latestVersionId($projectId);
            
        }
        if(isset($filter['hl'])) {
            if($langCheck = ProjectLanguage::where('hl', $filter['hl'])->first() ){
                $hl = $langCheck->id;
            }
        }
        
        $view =  ProjectScreen::where('status', self::STATUS_ACTIVE)
            ->where('project_version_id', $version)->where(function($where)use ($screenId){
              $where->where('id', $screenId)
                  ->orWhere('screen_name', $screenId);
            })->first();

        if(!$view) {
            return $view;
        } 

        $view->isSingle = true;
        
        
        $translation = [];
        if(!is_null($hl)) {
            $translation = ProjectScreenTranslationList::where('project_screen_id', $screenId)
                ->leftJoin('lang.prj_screen_trans_lang', 'prj_screen_trans_lang.screen_trans_id', 'prj_screen_trans.id')
                ->leftJoin('lang.project_language', 'project_language.id', 'prj_screen_trans_lang.trans_lang_id')
                ->where('project_language.id', $hl)
                // ->groupBy('prj_screen_trans.id')
                ->get()
                ->pluck('translation', 'key');
        }

        if(count($translation) < 1){
            // set fallback to default when no translation
            $translation = ProjectScreenTranslationList::where('project_screen_id', $screenId)->get()->pluck('default_translation', 'key');
        }

        $view->translationsGenerated = $translation;
        return $view;
    }

}
