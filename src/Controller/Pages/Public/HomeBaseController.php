<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public;

use App\Controller\BaseController;
use DI\DependencyException;
use DI\NotFoundException;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class HomeBaseController extends BaseController implements ControllerInterface {
    
    public function __construct(Engine $plates) {
        parent::__construct($plates);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        if ($this->container->get('logging_level') === 1) {
            $this->pagesLogger->info("Successful get page", [__CLASS__]);
        }
        return new Response(
            200,
            [],
            $this->plates->render('public::home',['title'=>"MuPIn - Museo Piemontese dell'Informatica"])
        );
    }
}
