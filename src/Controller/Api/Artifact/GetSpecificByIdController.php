<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use DI\ContainerBuilder;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetSpecificByIdController extends ControllerUtil implements ControllerInterface {

    protected ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(ContainerBuilder $builder, ArtifactSearchEngine $artifactSearchEngine) {
        parent::__construct($builder);        
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $category = $params["category"] ?? null;

        $error_message = null;

        if(!$id){
            $error_message = "Id not set!";
        }else if(!$category){
            $error_message = "Category not set!";
        }

        if ($error_message) {
            $this->api_log->info($error_message,[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
            );
        }

        try {
            $obj = $this->artifactSearchEngine->selectSpecificByIdAndCategory($id, $category);

            $this->api_log->info("Successfull get of specific artifact by id",[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                200,
                [],
                json_encode($obj)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(), 404)
            );
        }
    }
}
