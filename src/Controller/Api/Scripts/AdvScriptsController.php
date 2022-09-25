<?php

declare(strict_types=1);
namespace App\Controller\Api\Scripts;

use App\Controller\ControllerUtil;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class AdvScriptsController extends ControllerUtil implements ControllerInterface {    

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        
                
        $params = $request->getQueryParams();

        if(!isset($params["filename"])){
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!",400)
            );    
        }

        $filename = "secure_scripts/adv/".$params["filename"];

        if(!file_exists($filename)){
            return new Response(
                404,
                [],
                $this->getResponse("File not found!",404)
            ); 
        }

        return new Response(
            200,
            [],
            file_get_contents($filename)
        );
    }
}
