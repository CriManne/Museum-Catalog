<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Model\User;
use App\Service\UserService;
use SimpleMVC\Controller\ControllerInterface;


class GetUserPublicController implements ControllerInterface
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {   
        $this->userService = $userService;
    }


    public function execute(ServerRequestInterface $request,ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();        

        if(!isset($params["password"]) || 
           !isset($params["email"])){            
            return new Response(400,[],null);
        }

        $user = $this->userService->selectByCredentials(
            $params["email"],
            $params["password"],
            boolval($params["isAdmin"]) ?? false
        );

        if($user == null) return new Response(403,[],null);
        
        return new Response(200,[],$user);        
    }

}