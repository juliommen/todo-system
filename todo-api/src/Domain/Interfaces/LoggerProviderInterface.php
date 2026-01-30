<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

use Psr\Log\LoggerInterface;

interface LoggerProviderInterface
{
  public function create(): LoggerInterface;
}
