<?php
declare(strict_types=1);

namespace App\Exceptions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpException;
use Slim\Exception\HttpMethodNotAllowedException;
use Throwable;

class Handler
{
    public function __construct(private ResponseFactoryInterface $responseFactory) {}

    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        $status  = 500;
        $message = 'Internal Server Error';

        if ($exception instanceof HttpException) {
            $status  = $exception->getCode() ?: 500;
            $message = $exception->getMessage() ?: (string) $status;
        }

        $payload = [
            'error'   => true,
            'status'  => $status,
            'message' => $message,
            'path'    => $request->getUri()->getPath(),
            'method'  => $request->getMethod(),
        ];

        $response = $this->responseFactory->createResponse($status);

        if ($exception instanceof HttpMethodNotAllowedException) {
            $response = $response->withHeader('Allow', implode(', ', $exception->getAllowedMethods()));
        }

        return toResponse($response, $payload, $status);
    }
}
