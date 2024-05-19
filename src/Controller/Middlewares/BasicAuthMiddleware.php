<?php

declare(strict_types=1);

namespace App\Controller\Middlewares;

session_start();

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\HaltResponseFactory;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\Unauthorized;
use App\Plugins\Http\ResponseUtility;
use App\Service\UserService;
use DI\DependencyException;
use DI\NotFoundException;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

/**
 * Middleware to check if the request is made from an employee
 */
class BasicAuthMiddleware extends BaseController implements ControllerInterface
{
    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService)
    {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail() ?? null;

        if (!$userEmail) {
            $httpResponse = new Unauthorized();

            $this->apiLogger->info($httpResponse->getText(), [__CLASS__, $request->getRequestTarget()]);
            return HaltResponseFactory::create(
                response: $httpResponse,
                body: ResponseUtility::getHttpResponseBody($request, $httpResponse)
            );
        }

        try {
            $this->userService->findById($userEmail);

            $this->apiLogger->debug("Access granted", [__CLASS__, $userEmail, $request->getRequestTarget()]);

            return $response;
        } catch (ServiceException) {
            $httpResponse = new Unauthorized();

            $this->apiLogger->info($httpResponse->getText(), [__CLASS__, $userEmail, $request->getRequestTarget()]);
            return HaltResponseFactory::create(
                response: $httpResponse,
                body: ResponseUtility::getHttpResponseBody($request, $httpResponse)
            );
        } catch (RepositoryException $e) {
            $httpResponse = new InternalServerError($e->getMessage());

            $this->apiLogger->error($httpResponse->getText(), [__CLASS__, $userEmail, $request->getRequestTarget()]);
            return HaltResponseFactory::create(
                response: $httpResponse,
                body: ResponseUtility::getHttpResponseBody($request, $httpResponse)
            );
        }
    }
}
