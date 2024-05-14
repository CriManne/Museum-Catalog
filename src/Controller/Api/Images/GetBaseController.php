<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\BaseController;
use DI\DependencyException;
use DI\NotFoundException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetBaseController extends BaseController implements ControllerInterface {

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if (!$id) {
            $error_message = "Bad request!";
            $this->apiLogger->info($error_message, [__CLASS__]);
            return new Response(
                400,
                [],
                $this->getJson($error_message, 400)
            );
        }

        $dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/artifacts/";

        $files = scandir($dir);

        $regex = '/^' . $id . '_\d/';

        $files = array_map('strval', preg_filter('/^/', $dir, preg_grep($regex, $files)));

        $new_arr = [];

        if (count($files) == 1) {
            $files = [array_pop($files)];
        }

        foreach ($files as $file) {
            $new_arr[] = explode("public", $file)[1];
        }

        if($this->container->get('logging_level')===1){
            $this->apiLogger->info("Returned successfully ".count($new_arr)." images!", [__CLASS__]);
        }
        return new Response(
            200,
            [],
            json_encode($new_arr)
        );
    }
}
