<?php

declare(strict_types=1);
namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ComponentSearchEngine;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetSpecificByIdController extends ControllerUtil implements ControllerInterface{ 

    public ComponentSearchEngine $componentSearchEngine;
    
    public function __construct(
        ComponentSearchEngine $componentSearchEngine
    )
    {
        $this->componentSearchEngine = $componentSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $category = $params["category"] ?? null;

        /**
         * Get the list of artifact's categories
         */
        $categories = ArtifactsListController::$categories;

        /**
         * Return bad request response if no category is set or a wrong one
         */
        if (!$category || in_array($category, $categories)) {
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }

        foreach ($categories as $genericCategory) {            
            //Service full path
            $servicePath = "App\\Service\\$genericCategory\\$category" . "Service";

            try {
                /**
                 * Get service class, throws an exception if not found
                 */
                $this->componentService = $this->container->get($servicePath);

                $object = $this->componentSearchEngine->selectSpecificByIdAndCategory($id,$servicePath);

                return new Response(
                    200,
                    [],
                    json_encode($object)
                );
            }catch(ServiceException $e){
                return new Response(
                    400,
                    [],
                    $this->getResponse($e->getMessage(), 400)
                );
            } catch (Exception) {
            }
        }

        return new Response(
            400,
            [],
            $this->getResponse("Bad request!", 400)
        );
    }
}
