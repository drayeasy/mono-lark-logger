<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logging\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl\Util;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Utils;

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
    if (!extension_loaded('curl')) {
      throw new MissingExtensionException('The curl extension is needed to use the LarkHandler');
    }
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
    $postString = Utils::jsonEncode($postData);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://open.larksuite.com/open-apis/im/v1/messages?receive_id_type=" . $this->receiveType);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: Bearer " . $record->extra["lark_tenant_access_token"],
      "Content-Type: application/json; charset=utf-8"
    ]);

    $res = Util::execute($ch);

    curl_close($ch);
  }
}
