<?php

namespace App\Resources\Lang\PublicApi\Screen;

// use Illuminate\Http\Resources\Json\Resource as BaseResource;
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
        $data = [
            'id' => $this->id,
            'project_version_id' => $this->project_version_id,
            'screen_name' => $this->screen_name,
            'screen_description' => $this->screen_description,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
        if($this->isSingle) {
            $data['translations'] = $this->translationsGenerated;
        }
        return $data;
    }
    
}
