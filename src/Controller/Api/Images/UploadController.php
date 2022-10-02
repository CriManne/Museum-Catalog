<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\ControllerUtil;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class UploadController extends ControllerUtil implements ControllerInterface {

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $files = $_FILES['images'] ?? null;

        $error_message = null;

        if(!$id){
            $error_message = "No id set!";
        }else if(!$files){
            $error_message = "No file uploaded!";
        }

        if ($error_message) {
            $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($error_message, 400)
            );
        }

        self::uploadFiles($id, $files);

        $message = count($files)." files uploaded successfully!";
        if($this->container->get('logging_level')===1){
            $this->api_log->info($message, [__CLASS__, $_SESSION['user_email']]);
        }       

        return new Response(
            200,
            [],
            $this->getResponse($message)
        );
    }

    /**
     * Upload all the files
     * @param string $ObjectID The id to use to give images names
     * @param string $name The name of the $_FILES array index
     */
    public static function uploadFiles(string $ObjectID, string $name) {

        $files = $_FILES[$name];

        $path = $_SERVER['DOCUMENT_ROOT'] . "/assets/artifacts/";

        $index = 1;

        foreach ($files['tmp_name'] as $tmp_name) {
            $splittedName = explode('.', $files['name'][$index - 1]);
            $fileextension = end($splittedName);
            $filename = $path . $ObjectID . "_" . $index . "." . $fileextension;
            while (file_exists($filename)) {
                $index++;
                $filename = $path . $ObjectID . "_" . $index . "." . $fileextension;
            }
            move_uploaded_file($tmp_name, $filename);
            $index++;
        }
    }
}
