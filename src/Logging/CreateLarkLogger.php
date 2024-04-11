<?php

namespace App\Logging;


use App\Logging\Handlers\LarkHandler;
use App\Logging\Processors\LarkTokenProcessor;
use Monolog\Level;
use Monolog\Logger;

class CreateLarkLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        if (
            $config['larkAppId'] === null ||
            $config['larkAppSecret'] === null ||
            $config['larkAppReceiveId'] === null ||
            $config['larkAppReceiveType'] === null
        ) {
            throw new \InvalidArgumentException('The lark config is invalid.');
        }

        $logger = new Logger("lark");
        $logger->pushProcessor(new LarkTokenProcessor($config["larkAppId"], $config["larkAppSecret"]));
        $logger->pushHandler(
            new LarkHandler(
                $config["larkAppReceiveId"],
                $config["larkAppReceiveType"],
                Logger::toMonologLevel($config['level'] ?? Level::Alert)
            )
        );
        return $logger;
    }
}
