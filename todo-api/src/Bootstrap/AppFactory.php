<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Slim\Factory\AppFactory as SlimAppFactory;
use App\Bootstrap\Routes\V1Routes;

class AppFactory
{
    public static function createApp(): \Slim\App
    {
        $app = SlimAppFactory::create();
        $app->addBodyParsingMiddleware();
        Middlewares::configureCorsMiddleware($app);

        $logger = Infrastructure::createLogger();
        $taskRepo = Infrastructure::createTaskRepository();

        V1Routes::registerRoutes($app, $taskRepo);

        Middlewares::configureErrorMiddleware($app, $logger);

        return $app;
    }
}
