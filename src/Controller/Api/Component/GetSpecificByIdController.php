<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ComponentSearchEngine;
use DI\Container;
use DI\ContainerBuilder;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;
use Throwable;

class GetSpecificByIdController extends ControllerUtil implements ControllerInterface {

    public ComponentSearchEngine $componentSearchEngine;
    protected Container $container;

    public function __construct(
        ComponentSearchEngine $componentSearchEngine,
        ContainerBuilder $builder
    ) {
        parent::__construct();
        $this->componentSearchEngine = $componentSearchEngine;
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $category = $params["category"] ?? null;

        /**
         * Get the list of artifact's categories
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
        }else if(!$id){
            $error_message = "No id set!";
        }else if(!is_numeric($id)){
            $error_message = "Id must be numeric!";
        }

        if ($error_message) {
            $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
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

                $object = $this->componentSearchEngine->selectSpecificByIdAndCategory(intval($id), $servicePath);

                
                $this->api_log->info("Successfull get of specific component by id",[__CLASS__,$_SESSION['user_email']]);
                return new Response(
                    200,
                    [],
                    json_encode($object)
                );
            } catch (ServiceException $e) {
                $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    404,
                    [],
                    $this->getResponse($e->getMessage(), 404)
                );
            } catch (Throwable) {
            }
        }

        $error_message = "Bad request!";
        $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getResponse($error_message, 400)
        );
    }
}
