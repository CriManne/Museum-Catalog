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

class GetGenericByIdController extends ControllerUtil implements ControllerInterface {

    public ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(
        ArtifactSearchEngine $artifactSearchEngine
    ) {
        parent::__construct();
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if (!$id) {
            $this->api_log->info("No id is set",[__CLASS__]);
            return new Response(
                400,
                [],
                $this->getResponse("Bad request", 400)
            );
        }

        try {
            $obj = $this->artifactSearchEngine->selectGenericById($id);

            /**
             * If this is enabled it will generate a huge amount of 'useless' logs
             */
            //$this->api_log->info("Successfull get of generic artifact by id",[__CLASS__]);
            return new Response(
                200,
                [],
                json_encode($obj)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(),[__CLASS__]);
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(), 404)
            );
        }
    }
}
