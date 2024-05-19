<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\Images\UploadBaseController;
use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\NoContent;
use App\Plugins\Injection\DIC;
use App\SearchEngine\ArtifactSearchEngine;
use App\Service\IArtifactService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class UpdateBaseController extends BaseController implements ControllerInterface
{
    protected IArtifactService $artifactService;

    public function __construct(
        protected ArtifactSearchEngine $artifactSearchEngine)
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->apiHandler(
            function () use ($request, $response) {
                $userEmail = $this->getLoggedUserEmail();

                $params = $request->getParsedBody();

                $category = $params["category"] ?? null;
                unset($params["category"]);

                /**
                 * Get the list of artifact's categories
                 */
                $categories = ArtifactsListController::CATEGORIES;

                /**
                 * Return bad request response if no category is set or a wrong one
                 */
                $error_message = null;

                if (!$category) {
                    $error_message = "No category set!";
                } else if (!in_array($category, $categories)) {
                    $error_message = "Category not found!";
                }

                if ($error_message) {
                    $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

                    return ResponseFactory::createJson(
                        new BadRequest($error_message)
                    );
                }

                /**
                 * Get service class, throws an exception if not found
                 */
                $this->artifactService = DIC::getArtifactServiceByName($category);

                $instantiatedObject = $this->artifactService->fromRequest($params);

                $this->artifactService->update($instantiatedObject);

                //Upload new files
                UploadBaseController::uploadFiles($instantiatedObject->genericObject->id, 'images');

                $message = "$category updated successfully!";

                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new NoContent($message)
                );
            }
        );
    }
}
