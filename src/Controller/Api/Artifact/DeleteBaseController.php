<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\Images\DeleteBaseController as ImagesDeleteController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Models\User;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\NoContent;
use App\Plugins\Injection\DIC;
use App\Service\IArtifactService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class DeleteBaseController extends BaseController implements ControllerInterface
{
    protected IArtifactService $artifactService;

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $_SESSION[User::SESSION_EMAIL_KEY];

        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id       = $params["id"] ?? null;

        $categories = ArtifactsListController::CATEGORIES;

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!in_array($category, $categories)) {
            $error_message = "Category not found!";
        } else if (!$id) {
            $error_message = "No id set!";
        }

        if ($error_message) {
            $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($error_message)
            );
        }

        try {
            /**
             * Get service class, throws an exception if not found
             */
            $this->artifactService = DIC::getArtifactServiceByName($category);

            $this->artifactService->delete($id);

            ImagesDeleteController::deleteImages($id);

            $message = "Artifact with id {{$id}} deleted!";

            $this->apiLogger->info($message, [__CLASS__, $userEmail]);

            return ResponseFactory::create(
                new NoContent($this->getJson($message))
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

            return ResponseFactory::create(
                new BadRequest($this->getJson($e->getMessage()))
            );
        } catch (Throwable $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

            return ResponseFactory::create(
                new InternalServerError()
            );
        }
    }
}
