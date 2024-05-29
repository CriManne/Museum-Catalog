<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\Responses\Ok;
use App\SearchEngine\ComponentSearchEngine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetGenericsBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected ComponentSearchEngine $componentSearchEngine
    )
    {
        parent::__construct();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->apiHandler(
            function () use ($request, $response) {
                $userEmail = $this->getLoggedUserEmail();

                $params = $request->getQueryParams();

                $query    = $params["q"] ?? null;
                $category = $params["category"] ?? null;

                $allowedComponentCategories = ComponentsListController::CATEGORIES;

                $result = [];

                if ($query === "") {
                    $query = null;
                }

                $error_message = null;

                if (!$category) {
                    $error_message = "No category set!";
                } else if (!in_array($category, $allowedComponentCategories)) {
                    $error_message = "Category not found!";
                }

                if ($error_message) {
                    $this->apiLogger->debug($error_message, [__CLASS__, $userEmail]);

                    return ResponseFactory::createJson(
                        new NotFound($error_message)
                    );
                }

                if ($query) {
                    /**
                     * TODO: This can be removed since the query should match fully
                     */
                    $keywords = explode(" ", $query);

                    $result = $this->componentSearchEngine->selectGenerics($category, array_shift($keywords));

                    if (count($keywords) > 0) {
                        $resultsKeyword = [$result];
                        foreach ($keywords as $keyword) {
                            $resultsKeyword[] = $this->componentSearchEngine->selectGenerics($category, $keyword);
                        }

                        $result = array_uintersect(...$resultsKeyword, ...[
                            function ($a, $b) {
                                if ($a->objectId === $b->objectId) {
                                    return 0;
                                }
                                return -1;
                            }
                        ]);
                    }
                } else {
                    $result = $this->componentSearchEngine->selectGenerics($category);
                }

                if (count($result) < 1) {
                    $error_message = "No component found";

                    $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

                    return ResponseFactory::createJson(
                        new NotFound($error_message)
                    );
                }

                /**
                 * Investigate on this
                 */
                //Sometimes the result it's a key value array with one result
                if (count($result) == 1) {
                    $result = [array_pop($result)];
                }

                $this->apiLogger->debug("Successful get of generic components", [__CLASS__, $userEmail]);

                return ResponseFactory::create(
                    new Ok(json_encode($result))
                );
            }
        );
    }
}
