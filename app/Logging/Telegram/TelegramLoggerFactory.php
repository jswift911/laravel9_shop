<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use Monolog\Logger;

class TelegramLoggerHandler
{
    public function __invoke(array $config)
    {
        $logger = new Logger('telegram');
        $logger->setHandlers();
        return $logger;
    }
}
