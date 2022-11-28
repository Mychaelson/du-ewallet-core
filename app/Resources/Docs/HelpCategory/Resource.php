<?php

namespace App\Resources\Docs\HelpCategory;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Docs\HelpCategoryRepository;
use App\Models\Docs\HelpCategory;
use App\Repositories\Docs\HelpRepository;

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

        $fun = new HelpRepository;
        $category = $fun->SearchHelpByCategori($this->id);
        if($this->q){
            $q = $this->q;
            $category = $fun->SearchHelpByCategoriQ($q,$this->id);

        }

        $data = [
            'id' => $this->id ,
            'name' => $this->name ,
            'group' => $this->group ,
            'slug' => $this->slug ,
            'locale' => $this->locale ,
            'icon' => $this->icon ,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
        if($this->isSingle) {
            $data['category'] = $category;

        }

        return $data;
    }
    
}
