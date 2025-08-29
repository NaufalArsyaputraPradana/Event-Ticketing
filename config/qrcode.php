<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QR Code Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the QR code generation.
    | You can customize the default settings here.
    |
    */

    'format' => env('QR_CODE_FORMAT', 'png'),
    'size' => env('QR_CODE_SIZE', 300),
    'margin' => env('QR_CODE_MARGIN', 10),
    'error_correction' => env('QR_CODE_ERROR_CORRECTION', 'H'), // L, M, Q, H
    'encoding' => env('QR_CODE_ENCODING', 'UTF-8'),
    
    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where QR codes will be stored
    |
    */
    
    'storage' => [
        'disk' => env('QR_CODE_STORAGE_DISK', 'public'),
        'path' => env('QR_CODE_STORAGE_PATH', 'tickets'),
        'visibility' => env('QR_CODE_STORAGE_VISIBILITY', 'public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Data Structure
    |--------------------------------------------------------------------------
    |
    | Configure what data to include in the QR code
    |
    */
    
    'include_data' => [
        'ticket_id' => true,
        'ticket_code' => true,
        'ticket_number' => true,
        'event_title' => true,
        'event_date' => true,
        'venue' => true,
        'customer_name' => true,
        'customer_email' => true,
        'status' => true,
        'booking_code' => true,
        'scan_url' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Styling
    |--------------------------------------------------------------------------
    |
    | Configure the visual appearance of QR codes
    |
    */
    
    'style' => [
        'foreground_color' => env('QR_CODE_FOREGROUND_COLOR', '#000000'),
        'background_color' => env('QR_CODE_BACKGROUND_COLOR', '#FFFFFF'),
        'logo' => env('QR_CODE_LOGO', null),
        'logo_size' => env('QR_CODE_LOGO_SIZE', 0.3),
        'logo_margin' => env('QR_CODE_LOGO_MARGIN', 0),
    ],
];
