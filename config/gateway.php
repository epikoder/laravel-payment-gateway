<?php

use Epikoder\LaravelPaymentGateway\Currencies;

return [
    "providers" => [
        'paystack' => \Epikoder\LaravelPaymentGateway\Gateways\Paystack::class,
    ],

    /*
    |-------------------------------------------------------------------
    | Settings
    |-------------------------------------------------------------------
    | Provider related settings and keys
    | key|identifier, value|value
     */
    "settings" => [
        "paystack" => [
            "mode" => "test",
            'live' => [
                'sk_key' => 'sk_test_shdjkhdj827391nV4Lid',
                'currency' => Currencies::US_DOLLAR,
            ],
            'test' => [
                'sk_key' => 'sk_test_shdjkhdj827391nV4Lid',
                'currency' => Currencies::US_DOLLAR,
            ],
            'channels' => [
                'card', 'bank'
            ],
            'image' => 'https://tukuz.com/wp-content/uploads/2020/10/paystack-logo-vector.png',
        ]
    ],

    /**
     * Global Currency settings
     */
    "currency" => Currencies::US_DOLLAR,
    "rate" => 1,

    /**
     * Disabled gateways
     * this gateways will not be available to users but can be retrieved
     * using the getAllOptions() method of PaymentManager
     */
    'disabled' => ['stripe',],

    /**
     * Urls settings
     *
     * Edit the routes to actual routes available
     * in your application
     */
    "returnUrl" => 'checkout/success',
    "responseUrl" => "checkout/response",

    /**
     * Session keys
     */
    'payment_id' => 'test_payment_id',
    'provider_callback' => 'test_provider',
];
