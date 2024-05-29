<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private;

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\Found;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Session\SessionUtility;
use App\Service\UserService;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class HomeBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected UserService $userService
    )
    {
        parent::__construct();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     * @throws RepositoryException
     * @throws ServiceException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (isset($request->getQueryParams()['logout-btn'])) {
            if (!SessionUtility::isSessionStarted()) {
                session_start();
            }
            unset($_SESSION);
            session_destroy();

            return ResponseFactory::create(
                response: new Found(),
                headers: ['Location' => '/login']
            );
        }

        $userEmail = $this->getLoggedUserEmail();

        $user = $this->userService->findById($userEmail);

        $this->pagesLogger->info("Successful get page", [__CLASS__, $userEmail]);
        return ResponseFactory::createPage(
            response: new Ok(),
            templateName: 'private::home',
            data: ['title' => "Dashboard user", 'user' => $user]
        );
    }
}
