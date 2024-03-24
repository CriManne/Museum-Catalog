<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\Images\UploadController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class UpdateController extends ControllerUtil implements ControllerInterface {

    protected ArtifactSearchEngine $artifactSearchEngine;
    protected mixed $artifactService;
    protected mixed $artifactRepository;

    public function __construct(ArtifactSearchEngine $artifactSearchEngine) {
        parent::__construct();        
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getParsedBody();

        $category = $params["category"] ?? null;

        /**
         * Get the list of artifact's categories
         */
        $categories = ArtifactsListController::$categories;

        /**
         * Return bad request response if no category is set or a wrong one
         */
        $error_message = null;

        if(!$category){
            $error_message = "No category set!";
        }else if(!in_array($category,$categories)){
            $error_message = "Category not found!";
        }

        if ($error_message) {
            $this->api_log->info($error_message,[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
            );
        }

        //Repository full path
        $repoPath = "App\\Repository\\$category\\$category" . "Repository";
        //Service full path
        $servicePath = "App\\Service\\$category\\$category" . "Service";

        unset($params["category"]);

        try {
            /**
             * Get service class, throws an exception if not found
             */
            $this->artifactService = $this->container->get($servicePath);

            /**
             * Get repository class, throws an exception if not found
             */
            $this->artifactRepository = $this->container->get($repoPath);

            $instantiatedObject = $this->artifactRepository->returnMappedObject($params);

            $this->artifactService->update($instantiatedObject);

            //Upload new files
            UploadController::uploadFiles($instantiatedObject->objectId, 'images');

            $message = "$category updated successfully!";
            $this->api_log->info($message,[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getResponse($message)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        } catch (Throwable $e) {
            $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }
    }
}
