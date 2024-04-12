<?php

namespace Drayeasy\MonoLarkLogger;

use Drayeasy\MonoLarkLogger\Logging\Handlers\LarkHandler;
use Drayeasy\MonoLarkLogger\Logging\Processors\LarkTokenProcessor;
use Illuminate\Support\Facades\Log;

use Monolog\Logger as Monolog;
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
    Log::extend('lark', function ($app, array $config) {
      return new Monolog("lark", [
        new LarkHandler(
          $config['receive_id'],
          $config['receive_type'] ?? 'chat_id',
          Monolog::toMonologLevel($config['level'] ?? 'alert')
        )
      ], [
        new LarkTokenProcessor(
          $config['app_id'],
          $config['app_secret']
        )
      ]);
    });
  }

}
