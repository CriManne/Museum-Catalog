<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use DI\DependencyException;
use DI\NotFoundException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetGenericByIdController extends ControllerUtil implements ControllerInterface
{
    protected ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(ArtifactSearchEngine $artifactSearchEngine)
    {
        parent::__construct();
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if (!$id) {
            $error_message = "No id set!";
            $this->api_log->info($error_message, [__CLASS__]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
            );
        }

        try {
            $obj = $this->artifactSearchEngine->selectGenericById($id);

            if ($this->container->get('logging_level') === 1) {
                $this->api_log->info("Successfull get of generic artifact by id", [__CLASS__]);
            }

            return new Response(
                200,
                [],
                json_encode($obj)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(), [__CLASS__]);
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(), 404)
            );
        }
    }
}
