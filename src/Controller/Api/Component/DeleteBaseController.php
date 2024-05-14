<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class DeleteBaseController extends BaseController implements ControllerInterface
{
    protected mixed $componentService;

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

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
        $Artifactscategories = ArtifactsListController::CATEGORIES;

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!$id) {
            $error_message = "No id set!";
        } else if (!is_numeric($id)) {
            $error_message = "Id must be numeric!";
        } else if (!in_array($category, $Componentscategories)) {
            $error_message = "Category not found!";
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

                $this->componentService->delete(intval($id));

                $message = "$category deleted successfully!";
                $this->apiLogger->info($message, [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    200,
                    [],
                    $this->getJson($message)
                );
            } catch (ServiceException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    404,
                    [],
                    $this->getJson($e->getMessage(), 404)
                );
            } catch (RepositoryException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    400,
                    [],
                    $this->getJson("There are artifacts that are using this component, update them before deleting this!", 40)
                );
            } catch (Throwable) {
            }
        }

        $this->apiLogger->info("Bad request", [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getJson("Bad request!", 400)
        );
    }
}
