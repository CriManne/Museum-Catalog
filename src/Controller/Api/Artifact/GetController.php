<?php

declare(strict_types=1);
namespace App\Controller\Api\Artifact;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetController extends ControllerUtil implements ControllerInterface{ 

    public ArtifactSearchEngine $artifactSearchEngine;
    
    public function __construct(
        ArtifactSearchEngine $artifactSearchEngine
    )
    {
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        

        $params = $request->getQueryParams();
        
        if(isset($params["id"])){
            try{
                $query = $params["id"];
                $obj = $this->artifactSearchEngine->selectById($query);

                return new Response(
                    200,
                    [],
                    json_encode($obj)
                );
            }catch(ServiceException $e){
                return new Response(
                    404,
                    [],
                    $this->getResponse($e->getMessage(),404)
                );
            }            
        }

        return new Response(
            400,
            [],
            $this->getResponse("Bad request!",400)
        );
    }
}
