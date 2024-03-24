<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\SearchEngine\ArtifactSearchEngine;
use DI\DependencyException;
use DI\NotFoundException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetGenericsController extends ControllerUtil implements ControllerInterface {

    protected ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(ArtifactSearchEngine $artifactSearchEngine) {
        parent::__construct();        
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $params = $request->getQueryParams();

        $query = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        $categories = ArtifactsListController::$categories;

        $result = [];

        if ($query === "") {
            $query = null;
        }

        if ($category && !in_array($category, $categories)) {
            $error_message = "Category not found!";
            $this->api_log->info($error_message, [__CLASS__]);
            return new Response(
                404,
                [],
                $this->getResponse($error_message, 404)
            );
        }

        if ($query) {

            $keywords = explode(" ", $query);

            $result = $this->artifactSearchEngine->selectGenerics($category, array_shift($keywords));

            if (count($keywords) > 0) {
                $resultsKeyword = [$result];
                foreach ($keywords as $keyword) {
                    $resultsKeyword[] = $this->artifactSearchEngine->selectGenerics($category, $keyword);
                }

                $result = array_uintersect(...$resultsKeyword, ...[function ($a, $b) {
                    if ($a->objectId === $b->objectId) {
                        return 0;
                    }
                    return -1;
                }]);
            }
        } else {
            $result = $this->artifactSearchEngine->selectGenerics($category);
        }

        if (count($result) < 1) {
            /**
             * This happens when a user search and no results matches
             */
            if($this->container->get('logging_level')===1){
                $this->api_log->info("No object found!", [__CLASS__]);
            }
            return new Response(
                404,
                [],
                $this->getResponse("No object found", 404)
            );
        }

        //Sometimes the result it's a key value array with one result
        if (count($result) == 1) {
            $result = [array_pop($result)];
        }

        if($this->container->get('logging_level')===1){
            $this->api_log->info("Successfull get of generics artifacts!",[__CLASS__]);
        }

        return new Response(
            200,
            [],
            json_encode($result)
        );
    }
}
