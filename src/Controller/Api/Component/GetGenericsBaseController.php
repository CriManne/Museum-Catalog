<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\SearchEngine\ComponentSearchEngine;
use DI\DependencyException;
use DI\NotFoundException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetGenericsBaseController extends BaseController implements ControllerInterface
{
    protected ComponentSearchEngine $componentSearchEngine;

    public function __construct(ComponentSearchEngine $componentSearchEngine)
    {
        parent::__construct();
        $this->componentSearchEngine = $componentSearchEngine;
    }

    /**
     * @throws NotFoundException
     * @throws ServiceException
     * @throws DependencyException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $query = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        /**
         * Get the list of components's categories
         */
        $Componentscategories = ComponentsListController::$categories;

        $result = [];

        if ($query === "") {
            $query = null;
        }

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!in_array($category, $Componentscategories)) {
            $error_message = "Category not found!";
        }

        if ($error_message) {
            $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getJson($error_message, 404)
            );
        }

        if ($query) {

            $keywords = explode(" ", $query);

            $result = $this->componentSearchEngine->selectGenerics($category, array_shift($keywords));

            if (count($keywords) > 0) {
                $resultsKeyword = [$result];
                foreach ($keywords as $keyword) {
                    $resultsKeyword[] = $this->componentSearchEngine->selectGenerics($category, $keyword);
                }

                $result = array_uintersect(...$resultsKeyword, ...[function ($a, $b) {
                    if ($a->objectId === $b->objectId) {
                        return 0;
                    }
                    return -1;
                }]);
            }
        } else {
            $result = $this->componentSearchEngine->selectGenerics($category);
        }

        if (count($result) < 1) {
            $error_message = "No component found";
            $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getJson($error_message, 404)
            );
        }

        //Sometimes the result it's a key value array with one result
        if (count($result) == 1) {
            $result = [array_pop($result)];
        }

        /**
         * When loading components list
         */
        if ($this->container->get('logging_level') === 1) {
            $this->apiLogger->info("Successful get of generic components", [__CLASS__, $_SESSION['user_email']]);
        }
        return new Response(
            200,
            [],
            json_encode($result)
        );
    }
}
