<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\PingController;

/** @var App $app */
return static function (App $app): void {
    // Middleware globais
    $app->addBodyParsingMiddleware();

    // Rotas
    $app->get('/ping', PingController::class);

    // Você pode criar grupos
    // $app->group('/api', function (App $group) {
    //     $group->get('/ping', PingController::class);
    // });
};
