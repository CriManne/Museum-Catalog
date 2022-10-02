<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public\Artifact;

use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class ArtifactController extends ControllerUtil implements ControllerInterface {

    public function __construct(Engine $plates) {
        parent::__construct($plates);
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            $error_message = "No id set!";
            if ($this->container->get('logging_level') === 1) {
                $this->pages_log->info($error_message, [__CLASS__]);
            }
            return new Response(
                400,
                [],
                $this->displayError($error_message, 400)
            );
        }

        if ($this->container->get('logging_level') === 1) {
            $this->pages_log->info("Successfull get page", [__CLASS__]);
        }
        return new Response(
            200,
            [],
            $this->plates->render('artifact::single_artifact', ['title' => "Artifact"])
        );
    }
}
