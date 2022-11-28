<?php

namespace App\Resources\Docs\Document;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Docs\DocumentRepository;
use App\Models\Docs\Document;

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
            'id' => $this->id ,
            'title' => $this->title ,
            'slug' => $this->slug ,
            'locale' => $this->locale ,
            'version' => $this->version,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
        if($this->isSingle) {
            $data['content'] = $this->content;
        }

        return $data;
    }
    
}
