<?php

namespace App\Resources\Promotions\Stamp;

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
            'promotion_name' => (string) $this->promotion_name,
            'promotion_slug' => (string) $this->promotion_slug,
            // 'minimum_transaction_amount' => (int) $this->stampfilter['filter_compareto'] ?? 0,
            'date_from' => (string) $this->date_from,
            'date_to' => (string) $this->date_to,
            // 'promotion_value' => (float) $this->promotion_value,
            // 'description' => $this->stampproduct->description,
            // 'merchant_id' => (int) $this->merchant_id,
            // 'terms' => isset($this->properties['terms']) ?(string) implode("\n",$this->properties['terms']) : NULL,
            'images' => (array) isset($this->stampproduct->images) ? $this->stampproduct->images : [],
            'status' => (int) $this->status,
            'status_text' => $this->statusText($this->status),
        ];

    }

    function statusText($status=null) {
        switch ($status) {
            case 0:
                return 'New';
                break;

            case 1:
                return 'Active';
                break;

            case 2:
                return 'Need revision';
                break;

            case 3:
                return 'In review';
                break;

            case 4:
                return 'New revision';
                break;

            case 5:
                return 'Termination process';
                break;

            case 6:
                return 'Terminated';
                break;

            default:
                return null;
                break;
        }
    }
}
