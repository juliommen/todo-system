<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Providers;

use App\Infrastructure\Providers\Logger\LoggerProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class LoggerProviderTest extends TestCase
{
  public function testCreateReturnsLoggerInterfaceAndCreatesLogFile(): void
  {
    $logger = LoggerProvider::create();

    $this->assertInstanceOf(LoggerInterface::class, $logger);

    $ref = new \ReflectionClass(LoggerProvider::class);
    $providerPath = $ref->getFileName();
    $logPath = dirname($providerPath, 5) . '/logs/app.log';

    if (method_exists($logger, 'debug')) {
      $logger->debug('unit test log');
    }

    $this->assertFileExists($logPath);

    @unlink($logPath);
    @rmdir(dirname($logPath));
  }
}
