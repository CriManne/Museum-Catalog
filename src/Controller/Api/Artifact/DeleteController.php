<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\UserService;
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

class DeleteController extends ControllerUtil implements ControllerInterface {

    protected Container $container;

    public function __construct(ContainerBuilder $builder) {
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id = $params["id"] ?? null;

        if (!$category || !$id) {
            return new Response(
                400,
                [],
                $this->getResponse("Invalid request!", 400)
            );
        }

        $categories = ArtifactsListController::$categories;

        foreach ($categories as $singleCategory) {
            try {
                //Service full path
                $servicePath = "App\\Service\\$singleCategory\\$category" . "Service";

                /**
                 * Get service class, throws an exception if not found
                 */
                $this->artifactService = $this->container->get($servicePath);

                /**
                 * Get the delete id type name (string or int)
                 */
                $reflectionClass = new ReflectionClass($servicePath);
                $method = $reflectionClass->getMethod("delete");
                $methodType = $method->getParameters()[0]->getType()->getName();

                if ($methodType === "string") {
                    $this->artifactService->delete($id);
                } elseif ($methodType === "int") {
                    if (!is_numeric($id)) {
                        return new Response(
                            400,
                            [],
                            $this->getResponse("Bad request!", 400)
                        );
                    }
                    $this->artifactService->delete(intval($id));
                }

                return new Response(
                    200,
                    [],
                    $this->getResponse('Artifact with id {' . $id . '} deleted!')
                );
            } catch (ServiceException $e) {
                return new Response(
                    404,
                    [],
                    $this->getResponse($e->getMessage(), 404)
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
