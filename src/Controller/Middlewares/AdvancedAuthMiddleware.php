<?php

declare(strict_types=1);

namespace App\Controller\Middlewares;

use App\Controller\BaseController;
use App\Models\User;
use App\Plugins\Http\HaltResponseFactory;
use App\Plugins\Http\Responses\Unauthorized;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

/**
 * Middleware to check if the request is made from an administrator
 */
class AdvancedAuthMiddleware extends BaseController implements ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $_SESSION[User::SESSION_EMAIL_KEY] ?? null;
        $userPrivilege = $_SESSION[User::SESSION_PRIVILEGE_KEY] ?? null;

        if (!isset($userPrivilege) || $userPrivilege !== User::PRIVILEGE_SUPER_ADMIN) {
            $httpResponse = new Unauthorized();

            $this->apiLogger->info($httpResponse->getText(), [__CLASS__, $userEmail, $request->getRequestTarget()]);
            return HaltResponseFactory::create(
                response: $httpResponse,
                body: $this->getHttpResponseBody($request, $httpResponse)
            );
        }

        $this->apiLogger->debug("Access granted", [__CLASS__, $userEmail, $request->getRequestTarget()]);
        return $response;
    }
}
