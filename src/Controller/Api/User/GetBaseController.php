<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected UserService $userService
    ) {
        parent::__construct();
    }

    /**
     * @throws RepositoryException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();

        $params = $request->getQueryParams();
        try {
            $users = $this->userService->find(
                isset($params['page']) ? intval($params['page']) : null,
                isset($params['itemsPerPage']) ? intval($params['itemsPerPage']) : null,
                $params['query'] ?? null
            );

            $this->apiLogger->debug("Successful get of all users", [__CLASS__, $userEmail]);
            return ResponseFactory::create(
                new Ok(json_encode($users))
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);
            return ResponseFactory::createJson(
                new BadRequest($e->getMessage())
            );
        }
    }
}
