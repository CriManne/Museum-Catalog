<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);
namespace App\Controller\Api\Artifact;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Repository\GenericObjectRepository;
use App\Repository\GenericRepository;
use App\Service\GenericObjectService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetController extends ControllerUtil implements ControllerInterface{ 

    public GenericObjectService $genericObjectService;
    
    public function __construct(
        GenericObjectService $genericObjectService
    )
    {
        $this->genericObjectService = $genericObjectService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        

        $params = $request->getQueryParams();
        
        if(isset($params["q"])){
            try{
                $query = $params["q"];               

                $result = $this->genericObjectService->selectByQuery($query);

                return new Response(
                    200,
                    [],
                    json_encode($result)
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
            $this->getResponse("Invalid request!",400)
        );
    }
}
