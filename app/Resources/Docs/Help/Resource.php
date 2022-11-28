<?php

namespace App\Resources\Docs\Help;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Docs\HelpRepository;
use App\Repositories\Docs\HelpCategoryRepository;
use App\Models\Docs\Help;

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
        $fun = new HelpCategoryRepository();
        $category = $fun->GetHCategoryId($this->category)->toArray();

        $data = [
            'id' => (String) $this->id,
            // 'category' => $category, 
            'locale'  => $this->locale,
            'group' => $this->group,
            'title' => $this->title,
            'content' => $this->content,
            'keywords' => json_decode($this->keywords),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $data;
    }
    
}
