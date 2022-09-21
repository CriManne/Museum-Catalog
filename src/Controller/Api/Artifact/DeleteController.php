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

use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

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
            $servicePath = "App\\Service\\$category\\$category" . "Service";

            $builder = new ContainerBuilder();
            $builder->addDefinitions('config/container.php');
            $container = $builder->build();
            
            $this->artifactService = $container->get($servicePath);

            $this->artifactService->delete($id);

            return new Response(
                200,
                [],
                $this->getResponse('Artifact with id {' . $id . '} deleted!')
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
