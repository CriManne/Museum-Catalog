<?php

declare(strict_types=1);

namespace App\Controller\Api\Component;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Model\Response\GenericArtifactResponse;
use App\Repository\GenericObjectRepository;
use App\Repository\GenericRepository;
use App\SearchEngine\ComponentSearchEngine;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Monolog\Level;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetGenericsController extends ControllerUtil implements ControllerInterface {

    protected ComponentSearchEngine $componentSearchEngine;

    public function __construct(ComponentSearchEngine $componentSearchEngine) {
        parent::__construct();
        $this->componentSearchEngine = $componentSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $params = $request->getQueryParams();

        $query = $params["q"] ?? null;
        $category = $params["category"] ?? null;

        /**
         * Get the list of components's categories
         */
        $Componentscategories = ComponentsListController::$categories;

        $result = [];

        if ($query === "") {
            $query = null;
        }

        $error_message = null;

        if(!$category){
            $error_message = "No category set!";
        }else if(!in_array($category,$Componentscategories)){
            $error_message = "Category not found!";
        }

        if ($error_message) {
            $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getResponse($error_message, 404)
            );
        }

        if ($query) {            

            $keywords = explode(" ", $query);

            $result = $this->componentSearchEngine->selectGenerics($category, array_shift($keywords));

            if (count($keywords) > 0) {
                $resultsKeyword = [$result];
                foreach ($keywords as $keyword) {
                    $resultsKeyword[] = $this->componentSearchEngine->selectGenerics($category, $keyword);
                }

                $result = array_uintersect(...$resultsKeyword, ...[function ($a, $b) {
                    if ($a->ObjectID === $b->ObjectID) {
                        return 0;
                    }
                    return -1;
                }]);
            }
        } else {
            $result = $this->componentSearchEngine->selectGenerics($category);
        }

        if (count($result) < 1) {
            $error_message = "No component found";
            $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getResponse($error_message, 404)
            );
        }

        //Sometimes the result it's a key value array with one result
        if (count($result) == 1) {
            $result = [array_pop($result)];
        }

        /**
         * When loading components list
         */
        if($this->container->get('logging_level')===1){            
            $this->api_log->info("Successfull get of generic components",[__CLASS__,$_SESSION['user_email']]);
        }
        return new Response(
            200,
            [],
            json_encode($result)
        );
    }
}
