<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\Ok;
use App\SearchEngine\ArtifactSearchEngine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class GetGenericByIdBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected ArtifactSearchEngine $artifactSearchEngine)
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if (!$id) {
            $error_message = "No id set!";
            $this->apiLogger->info($error_message, [__CLASS__]);

            return ResponseFactory::createJson(
                new BadRequest($error_message)
            );
        }

        try {
            $obj = $this->artifactSearchEngine->selectGenericById($id);

            $this->apiLogger->debug("Successful get of generic artifact by id", [__CLASS__]);

            return ResponseFactory::create(
                new Ok(json_encode($obj))
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__]);
            return ResponseFactory::createJson(
                new BadRequest($e->getMessage())
            );
        }  catch (Throwable $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__]);

            return ResponseFactory::createJson(
                new InternalServerError()
            );
        }
    }
}
