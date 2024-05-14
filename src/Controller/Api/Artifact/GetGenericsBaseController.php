<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\Responses\Ok;
use App\SearchEngine\ArtifactSearchEngine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class GetGenericsBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected ArtifactSearchEngine $artifactSearchEngine)
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $query    = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        $categories = ArtifactsListController::CATEGORIES;

        if ($query === "") {
            $query = null;
        }

        if ($category && !in_array($category, $categories)) {
            $error_message = "Category not found!";
            $this->apiLogger->info($error_message, [__CLASS__]);

            return ResponseFactory::create(
                new NotFound($this->getJson($error_message))
            );
        }

        try {
            /**
             * TODO: This can be removed since the query should match fully
             */
            if ($query) {
                $keywords = explode(" ", $query);

                $result = $this->artifactSearchEngine->selectGenerics($category, array_shift($keywords));

                if (count($keywords) > 0) {
                    $resultsKeyword = [$result];
                    foreach ($keywords as $keyword) {
                        $resultsKeyword[] = $this->artifactSearchEngine->selectGenerics($category, $keyword);
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
                $result = $this->artifactSearchEngine->selectGenerics($category);
            }

            if (!$result) {
                $message = "No objects found";
                $this->apiLogger->debug($message, [__CLASS__]);

                return ResponseFactory::create(
                    new NotFound($this->getJson($message))
                );
            }

            /**
             * TODO: Investigate on this.
             */
            //Sometimes the result it's a key value array with one result
            if (count($result) == 1) {
                $result = [array_pop($result)];
            }

            $this->apiLogger->debug("Successful get of generics artifacts!", [__CLASS__]);

            return ResponseFactory::create(
                new Ok(json_encode($result))
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__]);
            return ResponseFactory::create(
                new BadRequest($this->getJson($e->getMessage()))
            );
        }  catch (Throwable $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__]);

            return ResponseFactory::create(
                new InternalServerError()
            );
        }
    }
}
