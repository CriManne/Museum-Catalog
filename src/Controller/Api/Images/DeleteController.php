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

class DeleteController extends ControllerUtil implements ControllerInterface{ 

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if(!$id){
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!",400)
            );
        }
        
        $counter = self::deleteImages($id);

        return new Response(
            200,
            [],
            $this->getResponse("Deleted $counter images!")
        );
    }

    public static function deleteImages(string $id){
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/artifacts/";

        $files = scandir($dir);

        $regex = '/^' . $id . '_\d/';

        $files = array_map('strval', preg_filter('/^/', $dir, preg_grep($regex, $files)));

        $counter = 0;
        foreach ($files as $file) {
            unlink($file);
            $counter++;
        }
        return $counter;
    }
}
