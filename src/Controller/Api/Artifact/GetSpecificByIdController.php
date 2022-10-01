<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetSpecificByIdController extends ControllerUtil implements ControllerInterface {

    public ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(
        ArtifactSearchEngine $artifactSearchEngine
    ) {
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $category = $params["category"] ?? null;

        if (!$id || !$category) {
            return new Response(
                400,
                [],
                $this->getResponse("Bad request", 400)
            );
        }

        try {
            $obj = $this->artifactSearchEngine->selectSpecificByIdAndCategory($id, $category);

            return new Response(
                200,
                [],
                json_encode($obj)
            );
        } catch (ServiceException $e) {
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(), 404)
            );
        }
    }
}
