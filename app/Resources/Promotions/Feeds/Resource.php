<?php

namespace App\Resources\Promotions\Feeds;

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
        


        $data = [
            'id' => (int) $this->id,
            'merchant_id' => (int) $this->merchant_id,
            'title' => (string) $this->title,
            'description' => (string) $this->description,
            'image' => (string) $this->image,
            'date_to' => (string) $this->date_to,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];

        if(isset($this->is_detail))
        {
            $meta = [];
            (isset($this->price)) ? $meta['price'] = (int) $this->price : false;
            (isset($this->price_discount)) ? $meta['price_discount'] = (int) $this->price_discount : false;
            (isset($this->highlight)) ? $meta['highlight'] = (string) $this->highlight : false;
            (isset($this->terms)) ? $meta['terms'] = (string) $this->terms : "";
            $meta['info'] = trans('messages.general.feed_experience_issue', ['merchant_name' => ($this->merchant->name ?? '') ]);
            $meta['merchant'] = [
                'name' => (int) $this->merchant->id ?? 0,
                'name' => (string) $this->merchant->name ?? '',
                'phone' => (string) $this->merchant->addresses[0]->phone ?? 0,
                'email' => (string) $this->merchant->addresses[0]->email ?? '',
            ];
            $data['meta'] = (object) $meta;
        }
        else{
            $data['action'] = (string) $this->action;
            $data['action_destiny'] = (string) $this->action_destiny;
        }

        return $data;
    }
}
