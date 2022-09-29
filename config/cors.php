<?php

return [

    'allow_origins' => [
        'http://localhost:8080',
        'http://localhost:3000',
        'http://*.app-readybc.com:3000'
    ],
    'allow_headers' => ['*'],
    'allow_methods' => ['*'],
    'allow_credentials' => true,
    'expose_headers' => [],
    'max_age' => 10,

];
