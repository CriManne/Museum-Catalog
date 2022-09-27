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

class UploadController extends ControllerUtil implements ControllerInterface{ 

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $files = $_FILES['images'] ?? null;

        if(!$id || !$files){
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!",400)
            );
        }
        try{
            self::uploadFiles($id,$files);

            return new Response(
                200,
                [],
                $this->getResponse("Files uploaded successfully!")
            );
        }catch(Exception){
            return new Response(
                400,
                [],
                $this->getResponse("Bad request",400)
            );
        }

    }

    /**
     * Upload all the files
     * @param string $ObjectID The id to use to give images names
     * @param string $name The name of the $_FILES array index
     * @throws Exception If an error while uploading the files occurred
     */
    public static function uploadFiles(string $ObjectID, string $name) {

        $files = $_FILES[$name];

        if($files['error']==UPLOAD_ERR_NO_FILE){
            throw new Exception();
        }

        if($files['error']!= UPLOAD_ERR_OK){
            throw new Exception();
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . "/assets/artifacts/";

        $index = 1;

        foreach ($files['tmp_name'] as $tmp_name) {
            $splittedName = explode('.', $files['name'][$index - 1]);
            $fileextension = end($splittedName);
            $filename = $path . $ObjectID . "_" . $index . "." . $fileextension;
            move_uploaded_file($tmp_name, $filename);
            $index++;
        }
    }
}
