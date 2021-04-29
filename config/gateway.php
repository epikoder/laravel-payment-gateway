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
                'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
                'currency' => Currencies::US_DOLLAR,
            ],
            'test' => [
                'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
                'currency' => Currencies::NAIRA,
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
    "currency" => Currencies::NAIRA,
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
     * Save persistent changes to database
     */
    'persistent_settings' => true,
    'persistent_table' => 'payment_gateways',

    /**
     * Session keys
     */
    'payment_id' => 'gateway_payment_id',
    'provider_callback' => 'gateway_provider_callback',
];
