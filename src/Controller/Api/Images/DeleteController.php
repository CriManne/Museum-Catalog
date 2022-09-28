<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\SearchEngine\ArtifactSearchEngine;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class DeleteController extends ControllerUtil implements ControllerInterface {

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $imgName = $params["image"] ?? null;

        if ($id) {
            $counter = self::deleteImages($id);

            return new Response(
                200,
                [],
                $this->getResponse("Deleted $counter images!")
            );
        }

        if($imgName){    
            try{
                $this->deleteImage($imgName);
                return new Response(
                    200,
                    [],
                    $this->getResponse("Image deleted!")
                );
            }catch(Exception $e){
                return new Response(
                    400,
                    [],
                    $this->getResponse($e->getMessage(),400)
                );
            }           
        }


        return new Response(
            400,
            [],
            $this->getResponse("Bad request!", 400)
        );
    }

    public function deleteImage(string $imgName) {
        $file = $_SERVER['DOCUMENT_ROOT'] ."/assets/artifacts/". $imgName;
        
        if(file_exists($file)){
            unlink($file);            
            return;
        }

        throw new Exception("Image not found!");
    }

    public static function deleteImages(string $id) {
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
