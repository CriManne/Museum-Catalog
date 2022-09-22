<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\CategoriesController;
use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
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
use TypeError;

class PostController extends ControllerUtil implements ControllerInterface {

    protected PDO $pdo;
    protected Container $container;

    public function __construct(PDO $pdo, ContainerBuilder $builder) {
        $this->pdo = $pdo;
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getParsedBody();

        $category = $params["category"] ?? null;

        if (!$category) {
            return new Response(
                400,
                [],
                $this->getResponse("Invalid request!", 400)
            );
        }

        //category title case
        $category = ucwords($category);

        $categories = CategoriesController::$categories;

        foreach ($categories as $singleCategory) {
            try {
                //Service full path
                $servicePath = "App\\Service\\$singleCategory\\$category" . "Service";
                //Class full path
                $classPath = "App\\Model\\$singleCategory\\$category";

                /**
                 * Get service class, throws an exception if not found
                 */
                $this->artifactService = $this->container->get($servicePath);

                unset($params["category"]);
                $rawObject = $params;

                $instantiatedObject = null;
                if (in_array($category, $categories)) {
                    //Repository full path
                    $repoPath = "App\\Repository\\$singleCategory\\$category" . "Repository";

                    /**
                     * Get service class, throws an exception if not found
                     */
                    $this->artifactRepo = $this->container->get($repoPath);

                    $instantiatedObject = $this->artifactRepo->returnMappedObject($rawObject);
                } else {
                    $instantiatedObject = ORM::getNewInstance($classPath, $rawObject);
                }

                $this->artifactService->insert($instantiatedObject);

                return new Response(
                    200,
                    [],
                    $this->getResponse("$category inserted successfully!")
                );
            } catch (ServiceException $e) {
                return new Response(
                    400,
                    [],
                    $this->getResponse($e->getMessage(), 400)
                );
            } catch (Exception) {
            }
        }

        return new Response(
            400,
            [],
            $this->getResponse("Bad request!", 400)
        );
    }
}
