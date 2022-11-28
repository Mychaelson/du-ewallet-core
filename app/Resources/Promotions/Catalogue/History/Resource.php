<?php

namespace App\Resources\Promotions\Catalogue\History;

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

        $name = "";
        if(NULL!=$this->point_id){
            $name = $this->pointRelation->name ?? "";
        }
        elseif (NULL!=$this->product_id) {
            $name = $this->product->product_name ?? "";
        }
        elseif ("SPENT"==$this->type) {
            $name = $this->identifier ?? '';
        }
        $pending = TRUE;
        if( (NULL !=$this->committed_at) )
        {
            $pending = FALSE;
        }
        if("SPENT"==$this->type)
        {
            $pending = FALSE;
        }

        $values = [
            'id' => (int) $this->id,
            'promotion_user_id' => (int) $this->promotion_user_id,
            'type' => (string) $this->type,
            'point' => (int) $this->point,
            'name' => (string) $name,
            'pending' => (bool) $pending,
            'created_at' => (string) $this->created_at,
        ];
        
        

        return $values;
    }
}
