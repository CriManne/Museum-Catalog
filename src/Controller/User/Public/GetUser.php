<?php

declare(strict_types=1);

namespace App\Controller\User\Public;

use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Model\User;
use App\Service\UserService;
use SimpleMVC\Controller\ControllerInterface;


class GetUser implements ControllerInterface
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

        $user = null;
        
        try{
            $user = $this->userService->selectByCredentials(
                $params["email"],
                $params["password"],
                isset($params["isAdmin"]) ? boolval($params["isAdmin"]) : null
            );                
            return new Response(200,[],json_encode($user));        
        }catch(ServiceException $e){
            return new Response(400,[],$e->getMessage());
        }catch(RepositoryException $e){
            return new Response(500,[],$e->getMessage());
        }        
    }

}