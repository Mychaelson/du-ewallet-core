<?php

return [

    'services' => [
        'pln' => App\Autopayment\Services\Pln::class,
        'bpjs' => App\Autopayment\Services\Bpjs::class,
        'cell-postpaid' => App\Autopayment\Services\Cellular::class,
        'gas' => App\Autopayment\Services\Gas::class,
        'insurance' => App\Autopayment\Services\Insurance::class,
        'multifinance' => App\Autopayment\Services\Multifinance::class,
        'pdam' => App\Autopayment\Services\Pdam::class,
        'pulsa' => App\Autopayment\Services\Pulsa::class,
        'telkom' => App\Autopayment\Services\Telkom::class,
        'tv' => App\Autopayment\Services\Tv::class,
    ],

    'inquiry_interval' => 3 //days
];
