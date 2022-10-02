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

            $message = "Deleted $counter images!";
            $this->api_log->info($message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getResponse($message)
            );
        }

        if ($imgName) {
            try {
                $this->deleteImage($imgName);

                $message = "Image deleted!";
                $this->api_log->info($message, [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    200,
                    [],
                    $this->getResponse($message)
                );
            } catch (Exception $e) {
                $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    400,
                    [],
                    $this->getResponse($e->getMessage(), 400)
                );
            }
        }

        $error_message = "Bad request!";
        $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            400,
            [],
            $this->getResponse($error_message, 400)
        );
    }

    public function deleteImage(string $imgName) {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/assets/artifacts/" . $imgName;

        if (file_exists($file)) {
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
