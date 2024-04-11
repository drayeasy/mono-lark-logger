<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Logging\Processors;

use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Generates a context from a Closure if the Closure is the only value
 * in the context
 *
 * It helps reduce the performance impact of debug logs if they do
 * need to create lots of context information. If this processor is added
 * on the correct handler the context data will only be generated
 * when the logs are actually logged to that handler, which is useful when
 * using FingersCrossedHandler or other filtering handlers to conditionally
 * log records.
 */
class LarkTokenProcessor implements ProcessorInterface
{


  private string $larkAppId;
  private string $larkAppSecret;


  public function __construct(string $larkAppId, string $larkAppSecret)
  {

    $this->larkAppId = $larkAppId;
    $this->larkAppSecret = $larkAppSecret;

  }

  public function __invoke(LogRecord $record): LogRecord
  {
    $context = $record->context;

    $token = Cache::get('lark_tenant_access_token');
    if (!$token) {
      $response = Http::post('https://open.larksuite.com/open-apis/auth/v3/tenant_access_token/internal', [
        'app_id' => $this->larkAppId,
        'app_secret' => $this->larkAppSecret,
      ]);
      if ($response->json()['code'] !== 0) {
        throw new MissingAppKeyException('App id or secret is wrong.');
      }
      $token = $response->json()['tenant_access_token'];
      Cache::put('lark_tenant_access_token', $token, 6400);
    }

    $record->extra['lark_tenant_access_token'] = $token;
    return $record;
  }
}
