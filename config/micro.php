<?php

return [


    'gateways' => [
        'default' =>[
            'url' => env('GATEWAY_URL', 'http://localhost'),
            'headers'=>[
            ]
        ]
    ],


    'coordinators'=>[
        'default' => [
            'gateway' => 'default',
            'url_prefix' => 'micromq'
        ]
    ]
];
