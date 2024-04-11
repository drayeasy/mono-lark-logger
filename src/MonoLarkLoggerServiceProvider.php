<?php

namespace Drayeasy\MonoLarkLogger;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MonoLarkLoggerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('mono-lark-logger');
    }

    public function packageBooted()
    {
        $this->publishes([
            __DIR__ . '/Logging' => app_path('Logging'),
        ], 'logging');

        Config::set('logging.channels.lark', [
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
