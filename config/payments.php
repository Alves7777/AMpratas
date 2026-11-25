<?php

$flag = fn (string $key, bool $default = false): bool => filter_var(
    env($key, $default),
    FILTER_VALIDATE_BOOLEAN
);

$mercadoPagoReady = filled(env('MERCADOPAGO_ACCESS_TOKEN'));

return [
    'default' => env('PAYMENT_DEFAULT', 'manual_whatsapp'),

    'mercadopago' => [
        'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
        'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
        'sandbox' => $flag('MERCADOPAGO_SANDBOX', true),
        'test_payer_email' => env('MERCADOPAGO_TEST_PAYER_EMAIL'),
    ],

    'gateways' => [
        'manual_whatsapp' => [
            'enabled' => true,
            'driver' => 'manual_whatsapp',
        ],
        'pix' => [
            'enabled' => $mercadoPagoReady && $flag('PAYMENT_PIX_ENABLED'),
            'driver' => env('PAYMENT_PIX_DRIVER', 'mercadopago'),
        ],
        'credit_card' => [
            'enabled' => $mercadoPagoReady && $flag('PAYMENT_CARD_ENABLED'),
            'driver' => env('PAYMENT_CARD_DRIVER', 'mercadopago'),
        ],
        'debit_card' => [
            'enabled' => $mercadoPagoReady && $flag('PAYMENT_DEBIT_ENABLED'),
            'driver' => env('PAYMENT_DEBIT_DRIVER', 'mercadopago'),
        ],
    ],
];
