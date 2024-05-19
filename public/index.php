<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

define('APP_PATH', getcwd());

require 'vendor/autoload.php';

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\ResponseUtility;
use App\Plugins\Injection\DIC;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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
    $baseController = new BaseController();

    $requestedUrl = $app->getRequest()->getRequestTarget();

    $log = new Logger('app_log');
    $log->pushHandler(new StreamHandler("./logs/app_log.log", DIC::getLoggingLevel()));

    $response      = $app->dispatch(); // PSR-7 response
    $status_code   = $response->getStatusCode();
    $error_message = $response->getReasonPhrase();

    if (!ResponseUtility::isRequestAPI($app->getRequest())
       && $status_code === (new NotFound())->getCode()
    ) {
        $notFoundResponse = new NotFound($error_message);
        $responseBody     = ResponseUtility::renderErrorPage($notFoundResponse);
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
        $responseBody = ResponseUtility::getHttpResponseBody($app->getRequest(), $httpResponse);
        $log->error($e->getMessage(), [$requestedUrl]);
    }

    SapiEmitter::emit(
        ResponseFactory::create(
            response: $httpResponse,
            body: $responseBody
        )
    );
}
