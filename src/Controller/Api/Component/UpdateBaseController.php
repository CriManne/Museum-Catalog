<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Models\User;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use App\SearchEngine\ComponentSearchEngine;
use App\Service\IComponentService;
use App\Util\ORM;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class UpdateBaseController extends BaseController implements ControllerInterface
{
    protected IComponentService $componentService;

    public function __construct(
        protected ComponentSearchEngine $componentSearchEngine
    )
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $_SESSION[User::SESSION_EMAIL_KEY];

        $params = $request->getParsedBody();

        $category = $params["category"] ?? null;
        unset($params["category"]);

        $allowedComponentCategories = ComponentsListController::CATEGORIES;
        $allowedArtifactCategories = ArtifactsListController::CATEGORIES;

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
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
                $this->componentService = DIC::getComponentServiceByName(
                    category: $genericCategory,
                    name: $category
                );

                $instantiatedObject = ORM::getNewModelInstance(
                    category: $genericCategory,
                    name: $category,
                    params: $params
                );

                $this->componentService->update($instantiatedObject);

                $message = "{$category} updated successfully!";
                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Ok($message)
                );
            } catch (ServiceException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new NotFound($e->getMessage())
                );
            } catch (Throwable) {
            }
        }

        $httpResponse = new BadRequest();
        $this->apiLogger->info($httpResponse->getText(), [__CLASS__, $userEmail]);

        return ResponseFactory::createJson($httpResponse);
    }
}
