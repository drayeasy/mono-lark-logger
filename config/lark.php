<?php

// config for Drayeasy/MonoLarkLogger
return [
    'channels' => [
        'lark' => [
            'driver' => 'custom',
            'via' => \App\Logging\CreateLarkLogger::class,
            'level' => env('LARK_LOG_LEVEL', 'alert'),
            'larkAppId' => env('LARK_APP_ID'),
            'larkAppSecret' => env('LARK_APP_SECRET'),
            'larkAppReceiveId' => env('LARK_APP_RECEIVE_ID'),
            'larkAppReceiveType' => env('LARK_APP_RECEIVE_TYPE'),
        ],
    ],
];
