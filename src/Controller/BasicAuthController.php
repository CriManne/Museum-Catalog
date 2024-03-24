<?php

declare(strict_types=1);

namespace App\Controller;

session_start();

use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Service\UserService;
use DI\DependencyException;
use DI\NotFoundException;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

/**
 * Middleware to check if the request is made from an employee
 */
class BasicAuthController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService) {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    /**
     * @throws RepositoryException
     * @throws NotFoundException
     * @throws \ReflectionException
     * @throws DependencyException
     * @throws ReflectionException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $requestedUrl = $request->getRequestTarget();

        $error_message = "";

        /**
         * Set the error to be displayed based on the request
         */
        if (str_contains($requestedUrl, 'api')) {
            $error_message = $this->getResponse("Unauthorized access", 401);
        } else {
            $error_message = $this->displayError("Unauthorized access", 401);
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
            
            $this->userService->findById($_SESSION['user_email']);

            if ($this->container->get('logging_level') === 1) {
                $this->api_log->info("Access granted", [__CLASS__, $_SESSION['user_email'], $request->getRequestTarget()]);
            }
            
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
