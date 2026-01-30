<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class LoggerProvider
{
    public static function create(): LoggerInterface
    {
        $logger = new Logger('app');
        $logPath = __DIR__ . '/../../../../logs/app.log';
        if (!is_dir(dirname($logPath))) {
            @mkdir(dirname($logPath), 0777, true);
        }
        $logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
        return $logger;
    }
}
