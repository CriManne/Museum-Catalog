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

class SearchController extends ControllerUtil implements ControllerInterface {

    public GenericObjectService $genericObjectService;

    public function __construct(
        GenericObjectService $genericObjectService
    ) {
        $this->genericObjectService = $genericObjectService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        if (isset($params["q"])) {
            $query = $params["q"];

            $keywords = explode(" ", $query);

            $result = $this->genericObjectService->selectByQuery(array_shift($keywords));

            if (count($keywords) > 0) {
                foreach ($keywords as $keyword) {
                    $resultKeyword = $this->genericObjectService->selectByQuery($keyword);
                    $result = array_uintersect($result, $resultKeyword, function ($a, $b) {
                        return $a == $b;
                    });
                }
            }

            if(count($result)<1){
                return new Response(
                    404,
                    [],
                    $this->getResponse("No object found",404)
                );
            }

            return new Response(
                200,
                [],
                json_encode($result)
            );
        }

        return new Response(
            400,
            [],
            $this->getResponse("Invalid request!", 400)
        );
    }
}
