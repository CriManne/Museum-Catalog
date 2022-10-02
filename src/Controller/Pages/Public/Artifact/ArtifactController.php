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

    public function __construct(ContainerBuilder $builder, Engine $plates) {
        parent::__construct($builder,$plates);        
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {       
        $params = $request->getQueryParams();

        if(!isset($params['id'])){
            return new Response(
                400,
                [],
                $this->displayError(400,"Bad request!")
            );
        }

        return new Response(
            200,
            [],
            $this->plates->render('artifact::single_artifact',['title'=>"Artifact"])
        );
    }
}
