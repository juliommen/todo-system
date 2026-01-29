<?php

declare(strict_types=1);

use App\Bootstrap\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

if (class_exists(\Dotenv\Dotenv::class)) {
    \Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
}

$app = AppFactory::createApp();
$app->run();
