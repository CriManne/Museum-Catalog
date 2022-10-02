<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
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
use Throwable;
use TypeError;

class DeleteController extends ControllerUtil implements ControllerInterface {

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id = $params["id"] ?? null;

        /**
         * Get the list of components's categories
         */
        $Componentscategories = ComponentsListController::$categories;

        /**
         * Get the list of artifacts's categories
         */
        $Artifactscategories = ArtifactsListController::$categories;

        $error_message = null;

        if(!$category){
            $error_message = "No category set!";
        }else if(!$id){
            $error_message = "No id set!";
        }else if(!is_numeric($id)){
            $error_message = "Id must be numeric!";
        }else if(!in_array($category,$Componentscategories)){
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

        foreach ($Artifactscategories as $genericCategory) {
            //Service full path
            $servicePath = "App\\Service\\$genericCategory\\$category" . "Service";

            try {
                /**
                 * Get service class, throws an exception if not found
                 */
                $this->componentService = $this->container->get($servicePath);

                $this->componentService->delete(intval($id));

                $message = "$category deleted successfully!";
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
            } catch (RepositoryException $e) {
                $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
                return new Response(
                    400,
                    [],
                    $this->getResponse("There are artifacts that are using this component, update them before deleting this!", 40)
                );
            } catch (Throwable) {
            }
        }

        $this->api_log->info("Bad request",[__CLASS__,$_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getResponse("Bad request!", 400)
        );
    }
}
