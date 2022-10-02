<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetController extends ControllerUtil implements ControllerInterface {

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if (!$id) {
            $error_message = "Bad request!";
            $this->api_log->info($error_message, [__CLASS__]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
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

        /**
         * If this is enabled it will generate a huge amount of 'useless' logs
        */
        $this->api_log->info("Returned successfully ".count($new_arr)." images!", [__CLASS__]);
        return new Response(
            200,
            [],
            json_encode($new_arr)
        );
    }
}
