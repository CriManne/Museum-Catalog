<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\Images\DeleteController as ImagesDeleteController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use Throwable;

class DeleteController extends ControllerUtil implements ControllerInterface
{
    protected mixed $artifactService;

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id = $params["id"] ?? null;

        $categories = ArtifactsListController::$categories;

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!in_array($category, $categories)) {
            $error_message = "Category not found!";
        } else if (!$id) {
            $error_message = "No id set!";
        }

        if ($error_message) {
            $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
            );
        }

        //Service full path
        $servicePath = "App\\Service\\$category\\$category" . "Service";

        try {
            /**
             * Get service class, throws an exception if not found
             */
            $this->artifactService = $this->container->get($servicePath);

            $this->artifactService->delete($id);
            ImagesDeleteController::deleteImages($id);

            $this->api_log->info("Artifact with id {' . $id . '} deleted!", [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getResponse('Artifact with id {' . $id . '} deleted!')
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(), 404)
            );
        } catch (Throwable $e) {
            $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }
    }
}
