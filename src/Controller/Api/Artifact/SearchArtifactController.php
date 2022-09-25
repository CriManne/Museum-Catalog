<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Model\Response\GenericArtifactResponse;
use App\Repository\GenericObjectRepository;
use App\Repository\GenericRepository;
use App\SearchEngine\ArtifactSearchEngine;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class SearchArtifactController extends ControllerUtil implements ControllerInterface
{

    public ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(
        ArtifactSearchEngine $artifactSearchEngine
    ) {
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $query = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        $result = [];

        if($query===""){
            $query = null;
        }

        if($category && !in_array($category,ArtifactsListController::$categories)){
            return new Response(
                404,
                [],
                $this->getResponse("Category not found!", 404)
            );
        }

        if ($query) {

            $keywords = explode(" ", $query);

            $result = $this->artifactSearchEngine->select($category,array_shift($keywords));

            if (count($keywords) > 0) {
                $resultsKeyword = [$result];
                foreach ($keywords as $keyword) {
                    $resultsKeyword[] = $this->artifactSearchEngine->select($category,$keyword);
                }

                $result = array_uintersect(...$resultsKeyword, ...[function ($a, $b) {
                    if ($a->ObjectID === $b->ObjectID) {
                        return 0;
                    }
                    return -1;
                }]);
            }
        } else {
            $result = $this->artifactSearchEngine->select($category);
        }

        if (count($result) < 1) {
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

        return new Response(
            200,
            [],
            json_encode($result)
        );
    }
}
