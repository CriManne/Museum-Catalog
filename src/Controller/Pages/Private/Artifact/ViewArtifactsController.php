<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use App\Service\UserService;
use DI\ContainerBuilder;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class ViewArtifactsController extends ControllerUtil implements ControllerInterface {
    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService)
    {
        parent::__construct($plates);
        $this->userService = $userService;
    }


    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $user = $this->userService->selectById($_SESSION['user_email']);

        $this->pages_log->info("Successfull get page", [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            200,
            [],
            $this->plates->render("p_artifact::view_artifacts", ['user' => $user,'title'=>"View artifacts"])
        );
    }
}
