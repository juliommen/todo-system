<?php

declare(strict_types=1);

namespace App\Bootstrap\Routes;

use App\Domain\Interfaces\TaskRepositoryInterface;
use Slim\App;
use App\Presentation\Controllers\V1\TaskController;

final class V1Routes
{
    public static function registerRoutes(App $app, TaskRepositoryInterface $taskRepo): void
    {
        $taskController = new TaskController($taskRepo);
        $taskController->registerRoutes($app);
    }
}
