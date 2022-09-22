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

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Model\Response\GenericObjectResponse;
use App\Repository\GenericObjectRepository;
use App\Repository\GenericRepository;
use App\Service\GenericObjectService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class SearchArtifactComponentController extends ControllerUtil implements ControllerInterface
{

    public GenericObjectService $genericObjectService;

    public function __construct(
        GenericObjectService $genericObjectService
    ) {
        $this->genericObjectService = $genericObjectService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $query = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        $result = [];

        if (isset($params["q"])) {

            $keywords = explode(" ", $query);

            $result = $this->genericObjectService->selectByQuery(array_shift($keywords), $category);

            if (count($keywords) > 0) {
                $resultsKeyword = [$result];
                foreach ($keywords as $keyword) {
                    $resultsKeyword[] = $this->genericObjectService->selectByQuery($keyword, $category);
                }

                $result = array_uintersect(...$resultsKeyword, ...[function ($a, $b) {
                    if ($a->ObjectID === $b->ObjectID) {
                        return 0;
                    }
                    return -1;
                }]);
            }
        } else {
            $result = $this->genericObjectService->selectAll($category);
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
