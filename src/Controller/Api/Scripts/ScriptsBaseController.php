<?php

declare(strict_types=1);

namespace App\Controller\Api\Scripts;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\Responses\Ok;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class ScriptsBaseController extends BaseController implements ControllerInterface
{

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();
        
        $params = $request->getQueryParams();

        if (!isset($params["filename"])) {
            $error_message = "No filename set!";
            $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($error_message)
            );
        }

        $filename = $this->container->get('baseScriptPath') . $params["filename"];

        if (!file_exists($filename)) {
            $error_message = "File {$filename} not found!";
            $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new NotFound($error_message)
            );
        }

        $this->apiLogger->debug("Successful get of {$filename} script", [__CLASS__]);

        return ResponseFactory::createJson(
            new Ok(file_get_contents($filename))
        );
    }
}
