<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */
    'app_domain' => env('APP_DOMAIN', 'test-readybc.com'),
    'locale' => env('APP_LOCALE', 'en'),
    'identity_pool' => [
        'driver' => env('IDENTITY_POOL', 'eloquent'),
        'guard' => env('IDENTITY_GUARD', 'jwt'),
    ],
    'pagination_size' => 10,
    'enable_database_log' => env('ENABLE_DATABASE_LOG', false)
];
