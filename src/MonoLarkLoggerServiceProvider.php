<?php

namespace Drayeasy\MonoLarkLogger;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\Logger as Monolog;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Drayeasy\MonoLarkLogger\Logging\Handlers\LarkHandler;
use Drayeasy\MonoLarkLogger\Logging\Processors\LarkTokenProcessor;

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
    Http::macro('lark', function () {
      return Http::withHeaders([
        'Content-Type' => 'application/json; charset=utf-8',
      ])->baseUrl('https://open.larksuite.com/open-apis');
    });

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
