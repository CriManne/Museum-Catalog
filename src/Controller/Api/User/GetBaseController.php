<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\Ok;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetBaseController extends BaseController implements ControllerInterface
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
                $users  = $this->userService->find(
                    isset($params['page']) ? intval($params['page']) : null,
                    isset($params['itemsPerPage']) ? intval($params['itemsPerPage']) : null,
                    $params['query'] ?? null
                );

                $this->apiLogger->debug("Successful get of all users", [__CLASS__, $userEmail]);
                return ResponseFactory::create(
                    new Ok(json_encode($users))
                );
            }
        );
    }
}
