<?php

return [

    /**
     * Phone prefix provider indonesia
     */
    'phone_prefix' => [
        'telkomsel' => ['0811','0812','0813','0821','0822','0823','0852','0853','0851'],
        'indosat' => ['0855','0856','0857','0858','0814','0815','0816'],
        'xl' => ['0817','0818','0819','0859','0877','0878'],
        'axis' => ['0838','0831','0832','0833'],
        'bolt' => ['0998','0999'],
        'tri' => ['0896','0897','0898','0899', '0895'],
        'smartfren' => ['0881','0882','0883','0884','0885','0886','0887','0888','0889']
    ],
    // asdas

    /**
     * OAuth user
     */
    'oauth' => [
        'url' => 'http://accounts.kuybermainyuks.com/auth/token',
        'client_id' => '2',
        'client_secret' => 'whDejJOIa21Gy6YNf0wFNWyCvMVnyV32tRGKUPnv',
        'public_key' => storage_path('oauth-public.key')
    ],

    /**
     * Promo Service
     */
    'promo' => [
        'url' => 'http://promotion.kuybermainyuks.com/api/v1',
        'client_id' => '23',
        'client_secret' => 'IiIIJWbfIzokGUIuNz9KAW73hNQXhZerYaWVJUF9'
    ],

    'point' => [
        'url' => 'http://point.kuybermainyuks.com/api/v2',
        'client_id' => '23',
        'client_secret' => 'IiIIJWbfIzokGUIuNz9KAW73hNQXhZerYaWVJUF9'
    ],

    'notif' => [
        'url' => 'http://notif.kuybermainyuks.com/api/v1',
        'client_id' => '23',
        'client_secret' => 'IiIIJWbfIzokGUIuNz9KAW73hNQXhZerYaWVJUF9'
    ],

    /**
     * Wallet Service
     */
    'wallet' => [
        'url' => 'http://wallet.kuybermainyuks.com/api',
        'client_id' => '30',
        'client_secret' => 'N9cpDkTUR32COTXUPJ72NZtV8ELl50tzwo0HEepq'
    ],

    'nusapay' => [
        'merchant_id' => 1
    ],

    'cashback_token' => [
        'client_id' => 2,
        'client_secret' => 'whDejJOIa21Gy6YNf0wFNWyCvMVnyV32tRGKUPnv',
    ]
];
