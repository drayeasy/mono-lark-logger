<?php

namespace Drayeasy\MonoLarkLogger;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Drayeasy\MonoLarkLogger\Commands\MonoLarkLoggerCommand;

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
            ->name('mono-lark-logger')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_mono-lark-logger_table')
            ->hasCommand(MonoLarkLoggerCommand::class);
    }
}
