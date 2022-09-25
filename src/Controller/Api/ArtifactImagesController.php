<?php

declare(strict_types=1);
namespace App\Controller\Api;

use App\Controller\ControllerUtil;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class ArtifactImagesController extends ControllerUtil implements ControllerInterface {    

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        
          
        $id = $request->getQueryParams()['id'] ?? null;

        if(!$id){
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!",400)
            );
        }

        $dir =$_SERVER['DOCUMENT_ROOT']."/assets/artifacts/";

        $files = scandir($dir);

        $regex = '/^'.$id.'_*/';

        $files = array_map('strval', preg_filter('/^/', $dir, preg_grep($regex, $files)));

        $new_arr = [];

        if(count($files)==1){
            $files = [array_pop($files)];
        }

        foreach($files as $file){
            $new_arr[] = explode("public",$file)[1];
        }

        return new Response(
            200,
            [],
            json_encode($new_arr)
        );
    }
}
