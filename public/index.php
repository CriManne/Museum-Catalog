<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use App\Controller\BaseController;
use App\Exception\RepositoryException;
use App\Plugins\Http\HttpCodes;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Injection\DIC;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Nyholm\Psr7\Response;
use SimpleMVC\App;
use SimpleMVC\Emitter\SapiEmitter;

try {
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
    $baseController = new BaseController($container->get(Engine::class));

    $requestedUrl = $app->getRequest()->getRequestTarget();

    $log = new Logger('app_log');
    $log->pushHandler(new StreamHandler("./logs/app_log.log", DIC::getLoggingLevel()));

    $response      = $app->dispatch(); // PSR-7 response
    $status_code   = $response->getStatusCode();
    $error_message = $response->getReasonPhrase();

    if (!$baseController->isRequestAPI($app->getRequest())
       && $status_code === 404
    ) {
        $notFoundResponse = new NotFound($error_message);
        $responseBody     = $baseController->getErrorPage($notFoundResponse);
        $log->info($error_message, [$requestedUrl]);
        $response = ResponseFactory::create(
            response: $notFoundResponse,
            body: $responseBody
        );
    }

    SapiEmitter::emit($response);
} catch (Exception $e) {
    $httpResponse = new InternalServerError($e->getMessage());
    $responseBody = null;

    if (isset($requestedUrl, $baseController, $log, $app)) {
        $responseBody = $baseController->getHttpResponseBody($app->getRequest(), $httpResponse);
        $log->error($e->getMessage(), [$requestedUrl]);
    }

    SapiEmitter::emit(
        ResponseFactory::create(
            response: $httpResponse,
            body: $responseBody
        )
    );
}
