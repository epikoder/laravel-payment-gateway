<?php

return [
    'providers' => [
    ],

    /**
     * Settings for each available gateway
     * name should be the the gateway identifier
     */
    'settings' => [
        'paystack' => [
            'live' => [
                'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
            ],
            'test' => [
                'sk_key' => 'sk_test_6f220edf6029757d56079cb33b047a15da7b3bfd',
                'pk_key' => 'pk_test_e8d8ffad357f6e7958b799ef96fb97965b13b959',
            ],
            'channels' => [
                'card', 'bank'
            ],
        ],
        'stripe' => [
            //
        ]
    ],

    /**
     * Disabled gateways
     * this gateways will not be available to users but can be retrieved
     * using the getAllOptions() method of PaymentManager
     */
    'disabled' => ['stripe',],

    /**
     * Save persistent changes to database
     */
    'persistent_settings' => true,
    'persistent_table' => 'payment_gateways',
];
