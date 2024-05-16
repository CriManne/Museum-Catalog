<?php

declare(strict_types=1);

namespace App\Controller\Api\Images;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Injection\DIC;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetBaseController extends BaseController implements ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $id = $params["id"] ?? null;

        if (!$id) {
            $httpResponse = new BadRequest();
            $this->apiLogger->info($httpResponse->getText(), [__CLASS__]);

            return ResponseFactory::createJson($httpResponse);
        }

        try {
            $dir = APP_PATH . DIC::getContainer()->get('artifactImages');

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

            $this->apiLogger->debug("Returned successfully " . count($new_arr) . " images!", [__CLASS__]);

            return ResponseFactory::createJson(
                new Ok(json_encode($new_arr))
            );
        } catch (Exception $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__]);

            return ResponseFactory::createJson(
                new BadRequest($e->getMessage())
            );
        }
    }
}
