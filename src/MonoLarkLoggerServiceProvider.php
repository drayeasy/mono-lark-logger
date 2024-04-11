<?php

namespace Drayeasy\MonoLarkLogger;

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

        // $this->mergeConfigFrom(
        //     __DIR__ . '/../config/lark.php',
        //     'logging'
        // );

        $this->app['config']->set('logging.channels.lark', [
            'driver' => 'custom',
            'via' => \App\Logging\CreateLarkLogger::class,
            'level' => env('LOG_LEVEL', 'alert'),
            'larkAppId' => env('LARK_APP_ID'),
            'LarkAppSecret' => env('LARK_APP_SECRET'),
            'LarkAppReceiveId' => env('LARK_APP_RECEIVE_ID'),
            'LarkAppReceiveType' => env('LARK_APP_RECEIVE_TYPE'),
        ]);
    }
}
