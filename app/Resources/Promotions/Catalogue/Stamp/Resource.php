<?php

namespace App\Resources\Promotions\Catalogue\Stamp;

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
            'stamp_id' =>  $this->stampcollection->id ?? null,
            // 'is_redeemed' => ($this->id_exchange) ? true : false,
            'name' => (string) $this->promotion_name,
            'date_from' => (string) $this->date_from,
            'date_to' => (string) $this->date_to,
            'images' => (array) $this->stampproduct->images,
            // 'used_budget' => (int) $this->used_budget,
            'stamp_collected' => (isset($this->stampcollection) && $this->stampcollection->available_stamp < $this->promotion_value ) ? (int) $this->stampcollection->available_stamp : 0,
            'stamp_required' => (int) $this->promotion_value,
            'meta' => [
                'merchant' => [
                    'id' => (int) $this->merchant->id ?? 0,
                    'name' => (string) $this->merchant->name ?? '',
                    'phone' => (string) $this->merchant->phone ?? '',
                    'email' => (string) $this->merchant->email ?? '',
                ],
                'highlight' => [
                    'title' => trans('messages.general.promotion.stamp_highlight.title'),
                    'content' => trans('messages.general.promotion.stamp_highlight.content', ['product' => $this->stampproduct->product_name ?? '']),
                ],
                'contact' => [
                    'title' => trans('messages.general.promotion.voucher_contact.title'),
                    'content' => trans('messages.general.promotion.voucher_contact.content', ['merchant' => ($this->merchant->name ?? '')]),
                ],
                'terms' => [
                    'title' => trans('messages.general.promotion.tnc.title'),
                    'content' => (string) $this->stampproduct->terms
                ],
                'description' => (string) $this->description,
            ],
            // 'meta' => [
            //     'highlight' => sprintf('Be our member and get FREE PRODUCT, Collect stamps towards free product every purchase %s, Get %sstamp', $this->minimum_transaction_amount ?? '50K', ''),
            //     'terms' => (array) $this->properties['terms'] ?? [],
            //     'merchant' => (array) [
            //         'id' => $this->merchant_id,
            //         'address_id' => $this->merchant->addresses[0]->id,
            //         'name' => sprintf("%s",$this->merchant->addresses[0]->name),
            //         'phone' => $this->merchant->addresses[0]->phone,
            //         'email' => $this->merchant->addresses[0]->email ?? '',
            //         'info' => sprintf("If you experience any issues with this stamp program, please contact %s", $this->merchant->name)
            //     ],
            // ],
        ];
        // if($this->id_exchange)
        // {
        //         $data['meta']['voucher_code'] = $this->voucher_code ?? $this->exchange_code;
        //         $data['meta']['qr_request_url'] = url('api/v1/rewards/qr/'. $this->exchange_code);
        //         $data['meta']['bar_request_url'] = url('api/v1/rewards/bar/'. $this->exchange_code);
        // }

        return $data;
    }
}
