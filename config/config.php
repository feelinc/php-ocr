<?php

return [

    'collector' => env('EXCHANGE_RATE_DRIVER', 'openexchangerate'),

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    */

    'database' => env('DB_CONNECTION', 'mysql'),
    'table' => env('EXCHANGE_RATE_TABLE', 'exchange_rates'),

    /*
    |--------------------------------------------------------------------------
    | Client
    |--------------------------------------------------------------------------
    |
    */

    'client' => [
        'user_agent'    => env('WEB_PARSER_CURL_USER_AGENT', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36'),
        'ip_address'    => env('WEB_PARSER_CURL_IP_ADDRESS', '18.96.236.10'),
        'options'       => [
            'timeout' => env('WEB_PARSER_CURL_TIMEOUT', 15), // seconds
            'debug'   => env('WEB_PARSER_CURL_DEBUG', false)
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Open OCR
    |--------------------------------------------------------------------------
    */

    'openexchangerate' => [
        'class' => Sule\OCR\Collector\OpenOCR::class, 
        'params' => [
            'app_id' => '1ee77654c7694a0097ebe6f4cfd6e801'
        ]
    ]

];
