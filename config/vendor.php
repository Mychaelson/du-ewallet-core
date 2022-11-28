<?php

return [
    'unipin' => [
        'voucher' => [
            'base_url'  => env('UNIPIN_VOUCHER_URL'),
            'user_id'   => env('UNIPIN_VOUCHER_USER'), // partner ID
            'secret'    => env('UNIPIN_VOUCHER_SECRET') // private key
        ],
        'flashtopup' => [
            'base_url'  => env('UNIPIN_FLASHTOPUP_URL'),
            'user_id'   => env('UNIPIN_FLASHTOPUP_USER'), // partner ID
            'secret'    => env('UNIPIN_FLASHTOPUP_SECRET') // private key
        ]

    ],
    'portalpulsa' => [
        'ip' => '172.104.161.223',
        'url' => env('PORTALPULSA_URL'),
        'user_id' => env('PORTALPULSA_ID'),
        'user_key' => env('PORTALPULSA_KEY'),
        'user_secret' => env('PORTALPULSA_SECRET'),
    ]
];