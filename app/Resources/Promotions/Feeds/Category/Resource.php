<?php

namespace App\Resources\Promotions\Feeds\Category;

use Illuminate\Http\Resources\Json\Resource as BaseResource;

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
        $properties = [];
        // if(is_array($this->properties)){
        //     $properties = array_map(function($v){
        //         return (int) $v;
        //     }, $this->properties);
        // }
        
        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'slug' => (string) $this->slug,
            'background' => (string) $this->background,
            'image' => (string) $this->image,
            'parent' => (string) $this->parent,
            'action' => (string) 'activity',
            'action_destiny' => (string) 'voucher-catalogue',
            
        ];
    }
}
