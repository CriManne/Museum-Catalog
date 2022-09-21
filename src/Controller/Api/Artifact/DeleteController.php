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
use DI\ContainerBuilder;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionFunction;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;
use TypeError;

class DeleteController extends ControllerUtil implements ControllerInterface {

    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
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

            $category = ucwords($category);
            $categories = CategoriesController::$categories;

            $builder = new ContainerBuilder();
            $builder->addDefinitions('config/container.php');
            $container = $builder->build();

            foreach ($categories as $singleCat) {
                try {
                    $servicePath = "App\\Service\\$singleCat\\$category" . "Service";
                    $this->artifactService = $container->get($servicePath);

                    try {
                        $this->artifactService->delete(intval($id));
                    } catch (TypeError) {
                        try {
                            $this->artifactService->delete($id);
                        } catch (TypeError) {
                        }
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
        } catch (ServiceException $e) {
            return new Response(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        }
    }
}
