<?php

namespace Drayeasy\MonoLarkLogger\Facades;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

class HttpClient extends Facade
{
  protected static function getFacadeAccessor(): string
  {
    return \Drayeasy\MonoLarkLogger\Facades\HttpClient::class;
  }

  public function __call($method, $parameters)
  {
    return Http::lark()->retry(3, 200)
      ->$method(...$parameters)
        ->throwIf(function (Response $response) {
          if ($response['code'] === 99991663) {
            Cache::forget('lark_tenant_access_token');
            return true;
          }
        });
  }
}
