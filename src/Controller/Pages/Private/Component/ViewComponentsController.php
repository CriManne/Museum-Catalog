<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Service\UserService;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class ViewComponentsController extends ControllerUtil implements ControllerInterface
{
    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService)
    {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $category = $params["category"] ?? null;

        if(!$category){
            return new Response(
                400,
                [],
                $this->displayError(400,"Bad request!")
            );
        }

        if(!in_array($category,ComponentsListController::$categories)){
            return new Response(
                404,
                [],
                $this->displayError(404,"Category not found!")
            );
        }

        $user = $this->userService->selectById($_SESSION['user_email']);

        return new Response(
            200,
            [],
            $this->plates->render("p_component::view_components",['user'=>$user,'title'=>"Add $category"])
        );
    }
}
