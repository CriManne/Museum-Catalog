<?php

declare(strict_types=1);
namespace App\Controller;
session_start();

use App\Controller\ControllerUtil;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class BasicAuthController extends ControllerUtil implements ControllerInterface {    

    public function __construct(Engine $plates) {
        parent::__construct($plates);
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {   
        if(!isset($_SESSION['user_email'])){
            $requestUrl = $request->getRequestTarget();    
            $error_message = "";
            if(explode('/',$requestUrl)[1] == 'api'){
                $error_message = $this->getResponse("Unauthorized access",401);
            }else{
                $error_message = $this->displayError(401,"Unauthorized access");
            }

            return new HaltResponse(
                401,
                [],
                $error_message
            );
        }
        return $response;
    }
}
