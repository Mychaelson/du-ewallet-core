<?php

namespace App\Resources\Lang\PublicApi\Project;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Lang\ProjectRepository;
use App\Models\Lang\ProjectVersion;

class Resource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $fun = new ProjectRepository;

        $data = [
            'id' => $this->id,
            'project_uid' => $this->project_uid,
            'project_description' => $this->project_description,
            'project_image' => $this->project_image,
            'latest_version' => $fun->latestVersion($this->id),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        if($this->isSingle) {
            $data['versions'] = $fun->versions($this->id);
        }

        return $data;
    }

    
}
