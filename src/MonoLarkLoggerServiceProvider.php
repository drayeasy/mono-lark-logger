<?php

namespace Drayeasy\MonoLarkLogger;

use Drayeasy\MonoLarkLogger\Commands\MonoLarkLoggerCommand;
use Illuminate\Support\Facades\Log;
use Logging\Handlers\LarkHandler;
use Logging\Processors\LarkTokenProcessor;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Monolog\Logger as Monolog;

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
