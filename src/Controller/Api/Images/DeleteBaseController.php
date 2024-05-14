<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\BaseController;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class DeleteBaseController extends BaseController implements ControllerInterface {

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;
        $imgName = $params["image"] ?? null;

        if ($id) {
            $counter = self::deleteImages($id);

            $message = "Deleted $counter images!";
            $this->apiLogger->info($message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getJson($message)
            );
        }

        if ($imgName) {
            try {
                $this->deleteImage($imgName);

                $message = "Image deleted!";
                $this->apiLogger->info($message, [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    200,
                    [],
                    $this->getJson($message)
                );
            } catch (Exception $e) {
                $this->apiLogger->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    400,
                    [],
                    $this->getJson($e->getMessage(), 400)
                );
            }
        }

        $error_message = "Bad request!";
        $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getJson($error_message, 400)
        );
    }

    /**
     * @throws Exception
     */
    public function deleteImage(string $imgName): void
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/assets/artifacts/" . $imgName;

        if (file_exists($file)) {
            unlink($file);
            return;
        }

        throw new Exception("Image not found!");
    }

    public static function deleteImages(string $id): int
    {
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
