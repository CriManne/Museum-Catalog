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

class DeleteBaseController extends BaseController implements ControllerInterface
{
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();

        $params = $request->getQueryParams();

        $id      = $params["id"] ?? null;
        $imgName = $params["image"] ?? null;

        try {
            if ($id) {
                $counter = self::deleteImages($id);

                $message = "Deleted {$counter} images!";

                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Ok($message)
                );
            }

            if ($imgName) {
                    $this->deleteImage($imgName);

                    $message = "Image deleted!";

                    $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                    return ResponseFactory::createJson(
                        new Ok($message)
                    );
            }
        } catch (Exception $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($e->getMessage())
            );
        }
        $httpResponse = new BadRequest();
        $this->apiLogger->info($httpResponse->getText(), [__CLASS__, $userEmail]);

        return ResponseFactory::createJson($httpResponse);
    }

    /**
     * @throws Exception
     */
    public function deleteImage(string $imgName): void
    {
        $file = APP_PATH . $this->container->get('artifactImages') . $imgName;

        if (file_exists($file)) {
            unlink($file);
            return;
        }

        throw new Exception("Image not found!");
    }

    /**
     * @param string $id
     *
     * @return int
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function deleteImages(string $id): int
    {
        $dir = APP_PATH . DIC::getContainer()->get('artifactImages');

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
