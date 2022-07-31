<?php

return [
    'defaults' => [
        'guard'     => 'api',
        'passwords' => 'users',
    ],
    'guards' => [
        'admin-api' => [
            'driver' => 'jwt',
            'provider' => 'admin-api',
        ],
        'client-api' => [
            'driver' => 'jwt',
            'provider' => 'client-api',
        ],
        'api' => [
            'driver' => 'cognito',
            'provider' => 'users',
        ],

    ],

//    'providers' => [
//
//        'admin-api' => [
//            'driver' => 'jwt',
//            'model' => \Encoda\Identity\Models\Cognito\CognitoUser::class
//        ],
//        'client-api' => [
//            'driver' => 'jwt',
//            'model' => \Encoda\Identity\Models\Cognito\CognitoUser::class
//        ],
//        'api' => [
//            'driver' => 'jwt',
//            'model' => \Encoda\Identity\Models\Cognito\CognitoUser::class
//        ],
//    ],

    'passwords' => [
        'api' => [
            'provider' => 'api',
            'table'    => 'user_password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'admin-api' => [
            'provider' => 'admin-api',
            'table'    => 'admin_password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],
];
