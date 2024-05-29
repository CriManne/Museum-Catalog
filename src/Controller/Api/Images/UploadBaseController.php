<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class UploadBaseController extends BaseController implements ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->apiHandler(
            function () use ($request, $response) {
                $userEmail = $this->getLoggedUserEmail();

                $params = $request->getQueryParams();

                $id    = $params["id"] ?? null;
                $files = $_FILES['images'] ?? null;

                $error_message = null;

                if (!$id) {
                    $error_message = "No id set!";
                } else if (!$files) {
                    $error_message = "No file uploaded!";
                }

                if ($error_message) {
                    $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);

                    return ResponseFactory::createJson(
                        new BadRequest($error_message)
                    );
                }

                self::uploadFiles($id, $files);

                $message = count($files) . " files uploaded successfully!";

                $this->apiLogger->debug($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Ok($message)
                );
            }
        );
    }

    /**
     * Upload all the files
     *
     * @param string $objectId The id to use to give images names
     * @param string $name     The name of the $_FILES array index
     *
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function uploadFiles(string $objectId, string $name): void
    {
        $files = $_FILES[$name];

        $path = APP_PATH . DIC::getContainer()->get('artifactImages');

        $index = 1;

        $indexInserted = 1;

        foreach ($files['tmp_name'] as $tmp_name) {
            $splittedName  = explode('.', $files['name'][$indexInserted - 1]);
            $fileextension = end($splittedName);
            $filename      = $path . $objectId . "_" . $index . "." . $fileextension;
            while (file_exists($filename)) {
                $index++;
                $filename = $path . $objectId . "_" . $index . "." . $fileextension;
            }
            move_uploaded_file($tmp_name, $filename);
            $index++;
            $indexInserted++;
        }
    }
}
