<?php

// config for Drayeasy/MonoLarkLogger
return [
    'channels' => [
        'lark' => [
            'driver' => 'custom',
            'via' => \App\Logging\CreateLarkLogger::class,
            'level' => env('LOG_LEVEL', 'alert'),
            'larkAppId' => env('LARK_APP_ID'),
            'LarkAppSecret' => env('LARK_APP_SECRET'),
            'LarkAppReceiveId' => env('LARK_APP_RECEIVE_ID'),
            'LarkAppReceiveType' => env('LARK_APP_RECEIVE_TYPE'),
        ],
    ],
];
