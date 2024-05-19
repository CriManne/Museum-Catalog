<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use App\SearchEngine\ComponentSearchEngine;
use App\Service\IService;
use App\Util\ORM;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class CreateBaseController extends BaseController implements ControllerInterface
{
    protected IService                 $componentService;

    public function __construct(
        protected ComponentSearchEngine $componentSearchEngine
    ) {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();

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
                $this->componentService = DIC::getComponentServiceByName($genericCategory, $category);

                $instantiatedObject = ORM::getNewModelInstance(
                    category: $genericCategory,
                    name: $category,
                    params: $params
                );

                $this->componentService->save($instantiatedObject);

                $message = "{$category} saved successfully!";
                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Ok($message)
                );
            } catch (\ReflectionException) {
                /**
                 * This case happens when the service is not found, in that case it will try
                 * the next service
                 */
            } catch (ServiceException $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new BadRequest($e->getMessage())
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
