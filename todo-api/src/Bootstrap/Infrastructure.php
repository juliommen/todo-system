<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Infrastructure\Providers\Logger\LoggerProvider;
use App\Infrastructure\Repositories\DoctrineTaskRepository;
use Psr\Log\LoggerInterface;

final class Infrastructure
{
    public static function createLogger(): LoggerInterface
    {
        return LoggerProvider::create();
    }

    public static function createTaskRepository(): DoctrineTaskRepository
    {
        $em = DoctrineTaskRepository::create();
        return new DoctrineTaskRepository($em);
    }
}
