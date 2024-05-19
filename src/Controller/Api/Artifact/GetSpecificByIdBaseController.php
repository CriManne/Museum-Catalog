<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use App\SearchEngine\ArtifactSearchEngine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetSpecificByIdBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected ArtifactSearchEngine $artifactSearchEngine)
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();

        $params = $request->getQueryParams();

        $id       = $params["id"] ?? null;
        $category = $params["category"] ?? null;

        $error_message = null;

        if (!$id) {
            $error_message = "Id not set!";
        } else if (!$category) {
            $error_message = "Category not set!";
        }

        if ($error_message) {
            $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($error_message)
            );
        }

        try {
            $obj = $this->artifactSearchEngine->selectSpecificByIdAndCategory($id, $category);

            $this->apiLogger->debug("Successful get of specific artifact by id", [__CLASS__, $userEmail]);

            return ResponseFactory::create(
                new Ok(json_encode($obj))
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($e->getMessage())
            );
        }
    }
}
