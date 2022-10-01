<?php

declare(strict_types=1);

namespace App\Controller;

session_start();

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Service\UserService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class BasicAuthController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService) {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $requestUrl = $request->getRequestTarget();
        $error_message = "";
        if (explode('/', $requestUrl)[1] == 'api') {
            $error_message = $this->getResponse("Unauthorized access", 401);
        } else {
            $error_message = $this->displayError(401, "Unauthorized access");
        }

        if (!isset($_SESSION['user_email'])) {
            $this->api_log->info("Unauthorized access", [__CLASS__, $request->getRequestTarget()]);
            return new HaltResponse(
                401,
                [],
                $error_message
            );
        }

        try {
            $this->userService->selectById($_SESSION['user_email']);

            /**
             * If this is enabled it will generate a huge amount of 'useless' logs
             */
            //$this->api_log->info("Access granted",[__CLASS__,$_SESSION['user_email'],$request->getRequestTarget()]);

            return $response;
        } catch (ServiceException) {
            $this->api_log->info("Unauthorized access", [__CLASS__, $_SESSION['user_email'], $request->getRequestTarget()]);
            return new HaltResponse(
                401,
                [],
                $error_message
            );
        }
    }
}
