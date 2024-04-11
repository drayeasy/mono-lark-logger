<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MonoLarkLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }


    public function boot(): void
    {
        $this->app['config']->set('logging.channels.lark', [
            'driver' => 'custom',
            'via' => \App\Logging\CreateLarkLogger::class,
            'level' => env('LARK_LOG_LEVEL', 'alert'),
            'larkAppId' => env('LARK_APP_ID'),
            'larkAppSecret' => env('LARK_APP_SECRET'),
            'larkAppReceiveId' => env('LARK_APP_RECEIVE_ID'),
            'larkAppReceiveType' => env('LARK_APP_RECEIVE_TYPE'),
        ]);
    }
}
