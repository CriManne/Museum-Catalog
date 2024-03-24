<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Service\UserService;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class GetController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * @throws \ReflectionException
     * @throws RepositoryException
     * @throws ReflectionException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $params = $request->getQueryParams();

            $users = $this->userService->find(
                isset($params['page']) ? intval($params['page']) : null,
                isset($params['itemsPerPage']) ? intval($params['itemsPerPage']) : null,
                $params['query'] ?? null
            );

            $this->api_log->info("Successfull get of all users", [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                json_encode($users)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
            return new HaltResponse(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        }
    }
}
