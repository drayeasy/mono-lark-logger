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

        $this->publishes([
            __DIR__ . '/MonoLarkLoggerServiceProvider.php' => app_path('Providers') . "/MonoLarkLoggerServiceProvider.php",
        ], 'providers');

    }
}
