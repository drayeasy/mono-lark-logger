<?php

namespace Drayeasy\MonoLarkLogger;

use Illuminate\Support\Facades\File;
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
            __DIR__ . '../config/lark.php' => config_path('lark.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . 'Logging' => app_path('Logging'),
        ], 'logging');

        if (File::exists(config_path('logging.php'))) {
            $loggingConfig = require config_path('logging.php');

            $loggingConfig['channels']['lark'] = [
                'driver' => 'custom',
                'via' => \App\Logging\CreateLarkLogger::class,
                'level' => env('LOG_LEVEL', 'alert'),
                'larkAppId' => env('LARK_APP_ID'),
                'LarkAppSecret' => env('LARK_APP_SECRET'),
                'LarkAppReceiveId' => env('LARK_APP_RECEIVE_ID'),
                'LarkAppReceiveType' => env('LARK_APP_RECEIVE_TYPE'),
            ];
            File::put(config_path('logging.php'), '<?php return ' . var_export($loggingConfig, true) . ';');
        }
    }
}
