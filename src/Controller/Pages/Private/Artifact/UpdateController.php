<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private\Artifact;

use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use App\Service\UserService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class UpdateController extends ControllerUtil implements ControllerInterface {
    protected UserService $userService;
    public ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(
        Engine $plates,
        UserService $userService,
        ArtifactSearchEngine $artifactSearchEngine
    ) {
        parent::__construct($plates);
        $this->userService = $userService;
        $this->artifactSearchEngine = $artifactSearchEngine;
    }


    /**
     * @throws RepositoryException
     * @throws \ReflectionException
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id = $params["id"] ?? null;

        $error_message = null;

        if(!$category){
            $error_message = "No category set!";
        }else if(!$id){
            $error_message = "No id set!";
        }

        if ($error_message) {
            $this->pages_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->displayError($error_message,400)
            );
        }

        if (!in_array($category, ArtifactsListController::$categories)) {
            $error_message = "Category not found!";
            $this->pages_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->displayError($error_message,404)
            );
        }

        $user = $this->userService->findById($_SESSION['user_email']);

        $this->pages_log->info("Successfull get page", [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            200,
            [],
            $this->plates->render("artifact_forms::$category", ['user' => $user,'title'=>"Update $category"])
        );
    }
}
