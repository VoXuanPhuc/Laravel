<?php

return [
    'callback_url' => 'https://app-readybc.com/cognito-callback/',
    'logout_url' => 'https://app-readybc.com/logouts-callback/',

    'client_id' => env('COGNITO_CLIENT_ID', null ),
    'client_secret' => env('COGNITO_CLIENT_SECRET', null ),
    'user_pool_id' => env('COGNITO_USER_POOL_ID', null )
];
