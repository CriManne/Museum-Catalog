<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use App\Service\UserService;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class UpdateArtifactController extends ControllerUtil implements ControllerInterface
{
    protected UserService $userService;
    public ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(
        Engine $plates, 
        UserService $userService,
        ArtifactSearchEngine $artifactSearchEngine
    )
    {
        parent::__construct($plates);
        $this->userService = $userService;
        $this->artifactSearchEngine = $artifactSearchEngine;
    }


    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $category = ucwords($params["category"]) ?? null;
        $id = $params["id"] ?? null;

        if(!$category || !$id){
            return new Response(
                400,
                [],
                $this->displayError(400,"Bad request!")
            );
        }

        if(!in_array($category,ArtifactsListController::$categories)){
            return new Response(
                404,
                [],
                $this->displayError(404,"Category not found!")
            );
        }

        $user = $this->userService->selectById($_SESSION['user_email']);

        try{
            $object = $this->artifactSearchEngine->selectByIdAndCategory($id,$category);

            return new Response(
                200,
                [],
                $this->plates->render("artifact_forms::$category",['user'=>$user,'object'=>$object])
            );
        }catch(ServiceException $e){
            return new Response(
                404,
                [],
                $this->getResponse($e->getMessage(),404)
            );
        }        
    }
}
