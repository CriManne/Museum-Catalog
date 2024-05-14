<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\SearchEngine\ComponentSearchEngine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class GetSpecificByIdBaseController extends BaseController implements ControllerInterface
{
    protected ComponentSearchEngine $componentSearchEngine;
    protected mixed $componentService;

    public function __construct(ComponentSearchEngine $componentSearchEngine)
    {
        parent::__construct();
        $this->componentSearchEngine = $componentSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $category = $params["category"] ?? null;

        /**
         * Get the list of components's categories
         */
        $Componentscategories = ComponentsListController::$categories;

        /**
         * Get the list of artifacts's categories
         */
        $Artifactscategories = ArtifactsListController::CATEGORIES;

        /**
         * Return bad request response if no category is set or a wrong one
         */
        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!in_array($category, $Componentscategories)) {
            $error_message = "Category not found!";
        } else if (!$id) {
            $error_message = "No id set!";
        } else if (!is_numeric($id)) {
            $error_message = "Id must be numeric!";
        }

        if ($error_message) {
            $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getJson($error_message, 400)
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

                $object = $this->componentSearchEngine->selectSpecificByIdAndCategory(intval($id), $servicePath);


                $this->apiLogger->info("Successful get of specific component by id", [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    200,
                    [],
                    json_encode($object)
                );
            } catch (ServiceException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    404,
                    [],
                    $this->getJson($e->getMessage(), 404)
                );
            } catch (Throwable) {
            }
        }

        $error_message = "Bad request!";
        $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getJson($error_message, 400)
        );
    }
}
