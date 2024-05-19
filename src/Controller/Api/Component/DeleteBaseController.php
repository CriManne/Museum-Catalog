<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use App\Service\IService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class DeleteBaseController extends BaseController implements ControllerInterface
{
    protected IService $componentService;

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();

        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id       = $params["id"] ?? null;

        $allowedComponentCategories = ComponentsListController::CATEGORIES;
        $allowedArtifactCategories  = ArtifactsListController::CATEGORIES;

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!$id) {
            $error_message = "No id set!";
        } else if (!is_numeric($id)) {
            $error_message = "Id must be numeric!";
        } else if (!in_array($category, $allowedComponentCategories)) {
            $error_message = "Category not found!";
        }

        if ($error_message) {
            $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($error_message)
            );
        }

        foreach ($allowedArtifactCategories as $genericCategory) {
            try {
                $this->componentService = DIC::getComponentServiceByName($genericCategory, $category);

                $this->componentService->delete($id);

                $message = "{$category} deleted successfully!";
                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Ok($message)
                );
            } catch (\ReflectionException) {

            } catch (ServiceException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new NotFound($e->getMessage())
                );
            } catch (RepositoryException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new BadRequest("There are artifacts that are using this component, 
                                                 update them before deleting this!")
                );
            } catch (Throwable $e) {
                $this->apiLogger->error($e->getMessage(), [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new InternalServerError()
                );
            }
        }

        $httpResponse = new BadRequest();
        $this->apiLogger->info($httpResponse->getText(), [__CLASS__, $userEmail]);

        return ResponseFactory::createJson($httpResponse);
    }
}
