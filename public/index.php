<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Nyholm\Psr7\Response;
use SimpleMVC\App;
use SimpleMVC\Emitter\SapiEmitter;

$builder = new ContainerBuilder();
$builder->addDefinitions('config/container.php');
$container = $builder->build();

// Store the configuration file in the container
$config = require 'config/app.php';
$container->set('config', $config);

$app = new App($container, $config);
$app->bootstrap();

/**
 * Util class that has response utility methods
 */
$util = new ControllerUtil($container->get(Engine::class));

$requestedUrl = $app->getRequest()->getRequestTarget();

$log = new Logger('app_log');
$log->pushHandler(new StreamHandler("./logs/app_log.log", Level::Error));

try {
    $response = $app->dispatch(); // PSR-7 response
    $status_code = $response->getStatusCode();
    $error_message = $response->getReasonPhrase();
    
    if ($status_code === 404 && !str_contains($requestedUrl, 'api')) {

        $responseBody = $util->displayError($error_message, $status_code);

        $log->error($error_message, [$requestedUrl]);
        SapiEmitter::emit(new Response(
            $status_code,
            [],
            $responseBody
        ));
    } else {
        SapiEmitter::emit($response);
    }
} catch (RepositoryException $e) {
    $responseBody = null;
    if (str_contains($requestedUrl, 'api')) {
        $responseBody = $util->getResponse($e->getMessage(), 500);
    } else {
        $responseBody = $util->displayError($e->getMessage(), 500);
    }
    $log->error($e->getMessage(), [$requestedUrl]);
    SapiEmitter::emit(new Response(
        500,
        [],
        $responseBody
    ));
}
