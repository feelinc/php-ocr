<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    */

    'driver' => env('OCR_DRIVER', 'tesseract'),

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    |
    */

    'services' => [
        'tesseract' => Sule\OCR\Service\Tesseract::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Tesseract
    |--------------------------------------------------------------------------
    |
    */

    'tesseract' => [
        'executable' => env('OCR_TESSERACT_EXECUTABLE', '/usr/bin/tesseract'),
        'enrich' => env('OCR_ENRICH_EXECUTABLE', base_path('vendor/sule/ocr/src/bin/enrich')),
        'textcleaner' => env('OCR_TEXTCLEANER_EXECUTABLE', base_path('vendor/sule/ocr/src/bin/textcleaner'))
    ]

];
