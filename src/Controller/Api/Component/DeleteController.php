<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

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

        $categories = ArtifactsListController::$categories;

        if (!$category || !$id || in_array($category, $categories) || !is_numeric($id)) {
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }

        foreach ($categories as $genericCategory) {            
            //Service full path
            $servicePath = "App\\Service\\$genericCategory\\$category" . "Service";

            try {
                /**
                 * Get service class, throws an exception if not found
                 */
                $this->componentService = $this->container->get($servicePath);

                $this->componentService->delete(intval($id));

                return new Response(
                    200,
                    [],
                    $this->getResponse("$category deleted successfully!")
                );
            }catch(ServiceException $e){
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
