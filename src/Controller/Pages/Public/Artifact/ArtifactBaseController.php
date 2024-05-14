<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public\Artifact;

use App\Controller\BaseController;
use DI\DependencyException;
use DI\NotFoundException;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class ArtifactBaseController extends BaseController implements ControllerInterface {

    public function __construct(Engine $plates) {
        parent::__construct($plates);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            $error_message = "No id set!";
            if ($this->container->get('logging_level') === 1) {
                $this->pagesLogger->info($error_message, [__CLASS__]);
            }
            return new Response(
                400,
                [],
                $this->getErrorPage($error_message, 400)
            );
        }

        if ($this->container->get('logging_level') === 1) {
            $this->pagesLogger->info("Successful get page", [__CLASS__]);
        }
        return new Response(
            200,
            [],
            $this->plates->render('artifact::single_artifact', ['title' => "Artifact"])
        );
    }
}
