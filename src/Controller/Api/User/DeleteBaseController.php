<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class DeleteBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected UserService $userService
    )
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->apiHandler(
            function () use ($request, $response) {
                $userEmail = $this->getLoggedUserEmail();

                $params = $request->getQueryParams();

                if (!isset($params['id'])) {
                    $error_message = "No email set!";
                    $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);
                    return ResponseFactory::createJson(
                        new BadRequest($error_message)
                    );
                }

                $this->userService->delete($params['id']);

                $message = 'User with id {' . $params['id'] . '} deleted!';
                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Ok($message)
                );
            }
        );
    }
}
