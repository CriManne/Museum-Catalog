<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public\Artifact;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class ArtifactBaseController extends BaseController implements ControllerInterface
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            $error_message = "No id set!";
            $this->pagesLogger->debug($error_message, [__CLASS__]);
            return ResponseFactory::createErrorPage(new BadRequest($error_message));
        }

        $this->pagesLogger->debug("Successful get page", [__CLASS__]);
        return ResponseFactory::createPage(
            response: new Ok(),
            templateName: 'artifact::single_artifact',
            data: ['title' => "Artifact"]
        );
    }
}
