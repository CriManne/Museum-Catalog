<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\Api\Images\DeleteController;
use App\Controller\Api\Images\UploadController;
use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\SearchEngine\ArtifactSearchEngine;
use App\SearchEngine\ComponentSearchEngine;
use App\Service\UserService;
use App\Util\ORM;
use DI\Container;
use DI\ContainerBuilder;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionFunction;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;
use Throwable;
use TypeError;

class CreateController extends ControllerUtil implements ControllerInterface {

    protected Container $container;
    protected ComponentSearchEngine $componentSearchEngine;

    public function __construct(ContainerBuilder $builder, ComponentSearchEngine $componentSearchEngine) {
        parent::__construct();
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
        $this->componentSearchEngine = $componentSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getParsedBody();

        $category = $params["category"] ?? null;

        /**
         * Get the list of components's categories
         */
        $categories = ComponentsListController::$categories;

        /**
         * Return bad request response if no category is set or a wrong one
         */
        $error_message = null;

        if(!$category){
            $error_message = "No category set!";
        }else if(!in_array($category,$categories)){
            $error_message = "Category not found!";
        }

        if ($error_message) {
            $this->api_log->info($error_message,[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
            );
        }

        foreach ($categories as $genericCategory) {
            //Class full path
            $classPath = "App\\Model\\$genericCategory\\$category";
            //Service full path
            $servicePath = "App\\Service\\$genericCategory\\$category" . "Service";

            try {
                /**
                 * Get service class, throws an exception if not found
                 */
                $this->componentService = $this->container->get($servicePath);

                unset($params["category"]);

                $instantiatedObject = ORM::getNewInstance($classPath, $params);

                $this->componentService->insert($instantiatedObject);

                $message = "$category inserted successfully!";
                $this->api_log->info($message,[__CLASS__,$_SESSION['user_email']]);
                return new Response(
                    200,
                    [],
                    $this->getResponse($message)
                );
            } catch (ServiceException $e) {
                $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
                return new Response(
                    404,
                    [],
                    $this->getResponse($e->getMessage(), 404)
                );
            } catch (Throwable) {
            }
        }

        $error_message = "Bad request!";
        $this->api_log->info($error_message,[__CLASS__,$_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getResponse($error_message, 400)
        );
    }
}
