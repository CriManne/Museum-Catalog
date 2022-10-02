<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class DeleteController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        parent::__construct();
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $params = $request->getQueryParams();

            if (!isset($params['id'])) {
                $error_message = "No email set!";
                $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
                return new Response(
                    400,
                    [],
                    $this->getResponse($error_message, 400)
                );
            }

            $this->userService->delete($params['id']);

            $message = 'User with id {' . $params['id'] . '} deleted!';
            $this->api_log->info($message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getResponse($message)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        }
    }
}
