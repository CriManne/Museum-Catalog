<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\Ok;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class HomeBaseController extends BaseController implements ControllerInterface
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->pagesLogger->debug("Successful get page", [__CLASS__]);
        return ResponseFactory::createPage(
            response: new Ok(),
            templateName: 'public::home',
            data: ['title' => "MuPIn - Museo Piemontese dell'Informatica"]
        );
    }
}
