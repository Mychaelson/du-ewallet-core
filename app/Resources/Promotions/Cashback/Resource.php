<?php

namespace App\Resources\Promotions\Cashback;

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
            'minimum_transaction_amount' => (string) $this->minimum_transaction_amount,
            'date_from' => (string) $this->date_from,
            'date_to' => (string) $this->date_to,
            'budget' => (int) $this->budget,
            'budget_available' => (int) $this->budget_available,
            'promotion_value' => (float) $this->promotion_value,
            'is_percentage' => (int) $this->is_percentage,
            'max_claim_amount_per_order' => (int) $this->max_claim_amount_per_order,
            'max_claim_amount_per_user' => (int) $this->max_claim_amount_per_user,
            'max_claim_per_user' => (int) $this->max_claim_per_user,
            'max_claim_per_day' => (int) $this->max_claim_per_day,
            'claim_from' => (string) $this->claim_from,
            'claim_to' => (string) $this->claim_to,
            'status' => (int) $this->status,
            'status_text' => $this->statusText($this->status),
            'merchant_id' => (int) $this->merchant_id,
            'nda_id' => (int) $this->nda_id,
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
