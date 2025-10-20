<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Retorna uma resposta JSON padronizada.
 */
if (!function_exists('toResponse')) {
    function toResponse(Response $response, array|object $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
