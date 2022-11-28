<?php

namespace App\Repositories\Connect;

use App\Models\Connect\AppsMerchant;

class AppsMerchantRepository
{
    public function __construct(private AppsMerchant $merchants)
    {
    }

    public function getByMerchantId($merchantId)
    {
        $merchant = $this->merchants->query()->firstWhere('merchant_id', $merchantId);

        return $merchant;
    }
}
