<?php

namespace App\Resources\Banner;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Carbon\Carbon; 
use App\Repositories\Banner\BannerRepository;
use App\Models\Banner\Banner;

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
            'id'            =>  $this->id,
            'image'         =>  $this->image,
            'cover'         =>  $this->cover,
            'title'         =>  $this->title,
            'highlight'     =>  $this->highlight,
            'terms'         =>  $this->terms,
            'activity'      =>  $this->activity,
            'label'         =>  $this->label,
            'web'           =>  $this->web,
            'phone'         =>  $this->phone,
            'email'         =>  $this->email,
            'time_start'    =>  $this->time_start,
            'time_end'      =>  $this->time_end,
            'group'         =>  $this->group,
            'params'        =>  json_decode($this->params),
            'status'        =>  $this->status,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $data;
    }
    
}
