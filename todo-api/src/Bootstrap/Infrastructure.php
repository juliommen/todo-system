<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Infrastructure\Providers\Logger\LoggerProvider;
use App\Infrastructure\Repositories\DoctrineTaskRepository;
use Psr\Log\LoggerInterface;

final class Infrastructure
{
    public static function createLogger(): LoggerInterface
    {
        return (new LoggerProvider())->create();
    }

    public static function createTaskRepository(): TaskRepositoryInterface
    {
        $em = DoctrineTaskRepository::create();
        return new DoctrineTaskRepository($em);
    }
}
