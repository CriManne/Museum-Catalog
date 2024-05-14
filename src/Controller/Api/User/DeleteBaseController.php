<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\BaseController;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Service\UserService;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class DeleteBaseController extends BaseController implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * @throws RepositoryException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $params = $request->getQueryParams();

            if (!isset($params['id'])) {
                $error_message = "No email set!";
                $this->apiLogger->info($error_message, [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    400,
                    [],
                    $this->getJson($error_message, 400)
                );
            }

            $this->userService->delete($params['id']);

            $message = 'User with id {' . $params['id'] . '} deleted!';
            $this->apiLogger->info($message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getJson($message)
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getJson($e->getMessage(), 400)
            );
        }
    }
}
