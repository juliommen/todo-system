<?php

declare(strict_types=1);

namespace App\Bootstrap\Routes;

use Slim\App;
use App\Presentation\Controllers\V1\TaskController;
use App\Infrastructure\Repositories\DoctrineTaskRepository;
use Psr\Log\LoggerInterface;

final class V1Routes
{
    public static function registerRoutes(App $app, DoctrineTaskRepository $taskRepo): void
    {
        $taskController = new TaskController($taskRepo);
        $taskController->registerRoutes($app);
    }
}
