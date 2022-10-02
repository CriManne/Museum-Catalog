<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\ControllerUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

/**
 * Middleware to check if the request is made from an administrator
 */
class AdvancedAuthController extends ControllerUtil implements ControllerInterface {

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        if (!isset($_SESSION['privilege']) || $_SESSION['privilege'] !== 1) {

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

            $this->api_log->info("Unauthorized access", [__CLASS__, $_SESSION['user_email'], $request->getRequestTarget()]);

            return new HaltResponse(
                401,
                [],
                $error_message
            );
        }

        if ($this->container->get('logging_level') === 1) {
            $this->api_log->info("Access granted", [__CLASS__, $_SESSION['user_email'], $request->getRequestTarget()]);
        }
        return $response;
    }
}
