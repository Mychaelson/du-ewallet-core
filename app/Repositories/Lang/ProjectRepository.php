<?php

namespace App\Repositories\Lang;


use Illuminate\Support\Facades\DB;
use App\Models\Lang\Project;
use App\Models\Lang\ProjectVersion;


class ProjectRepository
{


    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public function actionGetProjects()
    {
        return Project::paginate();
    }

    public function actionViewProject($projetId)
    {

        if(!$view = Project::where('status', self::STATUS_ACTIVE)->where('id', $projetId)->first()) {
            return $view;
        }
        $view->isSingle = true;
        return $view;
    }



    /*
     *
     *
     *
     */

    public function latestVersion($projectId)
    {
        $version = ProjectVersion::where('status', ProjectVersion::STATUS_ACTIVE)->where('project_id', $projectId)->orderBy('project_version', 'DESC')->first();
        return $version->project_version ?? 1;
    }

    public function versions($projectId)
    {
        $version = ProjectVersion::select('id', 'project_version')->where('status', ProjectVersion::STATUS_ACTIVE)->where('project_id', $projectId)->orderBy('project_version', 'ASC')->get();
        return $version;
    }

    public function latestVersionId($projectId)
    {
        $version = ProjectVersion::where('status', ProjectVersion::STATUS_ACTIVE)->where('project_id', $projectId)->orderBy('project_version', 'DESC')->first();
        return $version->id ?? 0;
    }

}
