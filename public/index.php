<?php
declare(strict_types=1);

use App\Exceptions\Handler;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// registra o middleware de erros e define o handler global
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(new Handler($app->getResponseFactory()));

// Carrega as rotas a partir do arquivo dedicado
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->run();
