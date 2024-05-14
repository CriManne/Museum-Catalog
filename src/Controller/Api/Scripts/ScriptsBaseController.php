<?php

declare(strict_types=1);

namespace App\Controller\Api\Scripts;

use App\Controller\BaseController;
use DI\DependencyException;
use DI\NotFoundException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class ScriptsBaseController extends BaseController implements ControllerInterface {

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        if (!isset($params["filename"])) {
            $error_message = "No filename set!";
            $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getJson($error_message, 400)
            );
        }

        $filename = "secure_scripts/basic/" . $params["filename"];

        if (!file_exists($filename)) {
            $error_message = "File {$filename} not found!";
            $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                404,
                [],
                $this->getJson($error_message, 404)
            );
        }

        if($this->container->get('logging_level')===1){
            $this->apiLogger->info("Successful get of $filename script",[__CLASS__]);
        }
        return new Response(
            200,
            [],
            file_get_contents($filename)
        );
    }
}
