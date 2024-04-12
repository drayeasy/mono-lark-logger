<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drayeasy\MonoLarkLogger\Logging\Handlers;

use Drayeasy\MonoLarkLogger\Facades\HttpClient;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Utils;
use Psy\Exception\RuntimeException;

/**
 * Sends notifications through Slack API
 *
 * @author Greg Kedzierski <greg@gregkedzierski.com>
 * @see    https://api.slack.com/
 */
class LarkHandler extends AbstractProcessingHandler
{
  private string $receiveID;
  private string $receiveType;

  public function __construct(string $receiveID, string $receiveType, int|string|Level $level = Level::Error, bool $bubble = true)
  {
    $this->receiveID = $receiveID;
    $this->receiveType = $receiveType;

    parent::__construct($level, $bubble);
  }

  /**
   * @inheritDoc
   */
  public function write(LogRecord $record): void
  {
    $postData = [
      "receive_id" => $this->receiveID,
      "msg_type" => "text",
      "content" => Utils::jsonEncode([
        "text" => $record->message
      ])
    ];

    $response = HttpClient::withHeaders([
      'Authorization' => 'Bearer ' . $record->extra["lark_tenant_access_token"]
    ])->post('/im/v1/messages?receive_id_type=' . $this->receiveType, $postData);

    if ($response->json()['code'] !== 0) {
      throw new RuntimeException('App id or secret is wrong.');
    }
  }
}
