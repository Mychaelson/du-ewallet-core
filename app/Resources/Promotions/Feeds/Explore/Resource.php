<?php

namespace App\Resources\Promotions\Feeds\Explore;

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
        return [
            'id' => (isset($this->id)) ? (int) $this->id : NULL,
            'title' => (string) $this->title,
            'type' => $this->getType($this->promotion_type),
            'background' => (isset($this->background)) ? (string) $this->background : NULL,
            'image' => (isset($this->image)) ? (string) $this->image : NULL,
            'action' => (string) $this->action,
            'action_destiny' => (string) $this->destiny,
            'button' => (isset($this->button)) ? (string) $this->button : NULL,
        ];
    }
    private function getType($type)
    {
        if(!$type){
            return NULL;
        }
        else
        {
            return (string) (4==$type) ? 'point' : 'stamp';
        }
    }
}
