<?php

return [


    'gateways' => [
        'default' =>[
            'url' => env('GATEWAY_API', 'http://localhost'),
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
