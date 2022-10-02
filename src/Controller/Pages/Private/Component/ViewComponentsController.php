<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private\Component;

use App\Controller\Api\ComponentsListController;
use App\Controller\ControllerUtil;
use App\Service\UserService;
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
            $error_message = "No category set!";
            $this->pages_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->displayError($error_message,400)
            );
        }

        if(!in_array($category,ComponentsListController::$categories)){
            $error_message = "Category not found!";
            $this->pages_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->displayError($error_message,404)
            );
        }

        $user = $this->userService->selectById($_SESSION['user_email']);

        $this->pages_log->info("Successfull get page", [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            200,
            [],
            $this->plates->render("p_component::view_components",['user'=>$user,'title'=>"Add $category"])
        );
    }
}
