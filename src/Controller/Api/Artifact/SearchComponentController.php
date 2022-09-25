<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\Response\GenericArtifactResponse;
use App\Repository\GenericObjectRepository;
use App\Repository\GenericRepository;
use App\SearchEngine\ComponentSearchEngine;
use App\Service\GenericObjectService;
use DI\Container;
use DI\ContainerBuilder;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class SearchComponentController extends ControllerUtil implements ControllerInterface
{

    public ComponentSearchEngine $componentSearchEngine;

    public function __construct(
        ComponentSearchEngine $componentSearchEngine
    ) {
        $this->componentSearchEngine = $componentSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $query = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        if (!$category) {
            return new Response(
                400,
                [],
                $this->getResponse("Bad request", 400)
            );
        }

        $categories = ArtifactsListController::$categories;

        //If the category is in the main category list then return not found
        if (in_array($category, $categories)) {
            return new Response(
                400,
                [],
                $this->getResponse("This category should be searched in artifacts and not in components!", 400)
            );
        }
        try {
            if ($query) {

                $keywords = explode(" ", $query);

                $result = $this->componentSearchEngine->select($category, array_shift($keywords));

                if (count($keywords) > 0) {
                    $resultsKeyword = [$result];
                    foreach ($keywords as $keyword) {
                        $resultsKeyword[] = $this->componentSearchEngine->select($category, $keyword);
                    }

                    $result = array_uintersect(...$resultsKeyword, ...[function ($a, $b) {
                        if (spl_object_hash($a) === spl_object_hash($b)) {
                            return 0;
                        }
                        return -1;
                    }]);
                }
            } else {
                $result = $this->componentSearchEngine->select($category);
            }
        } catch (RepositoryException $e) {
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(), 404)
            );
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
