<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Exception\HttpException;
use Throwable;

final class Middlewares
{
    public static function configureErrorMiddleware(App $app, LoggerInterface $logger): void
    {
        $displayErrorDetails = filter_var(getenv('APP_ENV') === 'development', FILTER_VALIDATE_BOOLEAN);
        $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true, $logger);

        $defaultHandler = function (
            ServerRequestInterface $request,
            Throwable $exception,
            bool $displayErrorDetails,
        ) use (
            $app,
            $logger
        ) {
            $status = 500;

            if ($exception instanceof HttpException) {
                $status = $exception->getCode();
            }

            $logger->error($exception->getMessage(), ['exception' => $exception]);

            $payload = [
                'error' => [
                    'message' => $displayErrorDetails ? $exception->getMessage() : 'Internal Server Error',
                ],
            ];

            $response = $app->getResponseFactory()->createResponse($status);
            $response->getBody()->write(json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json');
        };

        $errorMiddleware->setDefaultErrorHandler($defaultHandler);
    }

    public static function configureCorsMiddleware(App $app): void
    {
        $allowedOrigin = getenv('CORS_ORIGIN') ?: '*';

        $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app, $allowedOrigin) {
            $responseFactory = $app->getResponseFactory();

            if (strtoupper($request->getMethod()) === 'OPTIONS') {
                $response = $responseFactory->createResponse(200);
            } else {
                $response = $handler->handle($request);
            }

            return $response
                ->withHeader('Access-Control-Allow-Origin', $allowedOrigin)
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Credentials', 'true');
        });
    }
}
