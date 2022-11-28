<?php

namespace App\Resources\Promotions\Catalogue;

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
            'description' => (string) $this->description,
            'icon' => $this->isString($this->icon),
            'background' => (string) $this->background,
            'image' => (string) $this->image,
            'status' => (int) $this->status,
            'parent' => $this->isInt($this->parent),
        ];

    }

    private function isString(?string $data)
    {
        return $data;
    }

    private function isInt(?int $data)
    {
        return $data;
    }
}
