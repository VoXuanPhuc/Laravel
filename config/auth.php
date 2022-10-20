<?php

return [
    'defaults' => [
        'guard'     => 'api',
        'passwords' => 'users',
        'provider' => 'cognito'
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
            'driver' =>  config('config.identity_pool.guard'),
            'provider' => 'cognito',
        ],

    ],

    'providers' => [

        'users' => [
            'driver' => 'eloquent',
            'model'  =>  \Encoda\Identity\Models\Database\User::class,
        ],
        'cognito' => [
            'driver' => 'cognito',
            'model' => \Encoda\AWSCognito\Models\CognitoUser::class,
        ]
    ],

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
