<?php
/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use App\Controller\ViewsUtil;
use App\Exception\RepositoryException;
use DI\ContainerBuilder;
use League\Plates\Engine;
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

try{
    $response = $app->dispatch(); // PSR-7 response
    SapiEmitter::emit($response);
}catch(RepositoryException $e){    
    $util = new ViewsUtil($container->get(Engine::class));

    echo $util->displayError(500,$e->getMessage());    
}
