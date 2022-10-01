<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
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
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
        $this->componentSearchEngine = $componentSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getParsedBody();

        $category = $params["category"] ?? null;

        /**
         * Get the list of artifact's categories
         */
        $categories = ArtifactsListController::$categories;

        /**
         * Return bad request response if no category is set or a wrong one
         */
        if (!$category || in_array($category, $categories)) {
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
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

                return new Response(
                    200,
                    [],
                    $this->getResponse("$category inserted successfully!")
                );
            } catch (ServiceException $e) {
                return new Response(
                    404,
                    [],
                    $this->getResponse($e->getMessage(), 404)
                );
            } catch (Throwable) {
            }
        }

        return new Response(
            400,
            [],
            $this->getResponse("Bad request!", 400)
        );
    }
}
