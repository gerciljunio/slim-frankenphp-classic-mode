<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// Error handler global SEMPRE JSON
$responseFactory = $app->getResponseFactory();
$customErrorHandler = function (
    Request $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($responseFactory): Response {
    $status = 500;
    $message = 'Internal Server Error';

    if ($exception instanceof \Slim\Exception\HttpException) {
        $status  = $exception->getCode() ?: 500;
        $message = $exception->getMessage() ?: (string) $status;
    }

    $payload = [
        'error'  => true,
        'status' => $status,
        'message'=> $message,
        'path'   => $request->getUri()->getPath(),
        'method' => $request->getMethod(),
    ];

    $response = $responseFactory->createResponse($status);

    if ($exception instanceof \Slim\Exception\HttpMethodNotAllowedException) {
        $response = $response->withHeader('Allow', implode(', ', $exception->getAllowedMethods()));
    }

    return toResponse($response, $payload, $status);
};

// registre o middleware de erros e só depois defina o handler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

// Carrega as rotas a partir do arquivo dedicado
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->run();
