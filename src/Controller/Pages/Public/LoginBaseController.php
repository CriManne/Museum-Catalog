<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public;

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Models\User;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\Found;
use App\Plugins\Http\Responses\Ok;
use App\Plugins\Session\SessionUtility;
use App\Service\UserService;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use SimpleMVC\Controller\ControllerInterface;

class LoginBaseController extends BaseController implements ControllerInterface
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
     * @throws DependencyException
     * @throws NotFoundException
     * @throws RepositoryException
     * @throws ReflectionException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $sessionValid = false;

        if (!SessionUtility::isSessionStarted()) {
            session_start();
        }

        if (isset($userEmail)) {
            $sessionValid = true;
        }

        $credentials = $request->getParsedBody();

        if (!$sessionValid && (!isset($credentials["submitLogin"], $credentials["email"], $credentials["password"]))) {
            $this->pagesLogger->debug("Successful get page", [__CLASS__]);

            return ResponseFactory::createPage(
                response: new Ok(),
                templateName: 'public::login',
                data: ['title' => "Login"]
            );
        }

        try {
            $userEmail = $this->getLoggedUserEmail();

            $user = null;

            if ($sessionValid) {
                $user = $this->userService->findById($userEmail);
            } else {
                $user = $this->userService->findByCredentials($credentials["email"], $credentials["password"]);
            }

            $_SESSION[User::SESSION_EMAIL_KEY]     = $user->email;
            $_SESSION[User::SESSION_PRIVILEGE_KEY] = $user->privilege;

            return ResponseFactory::create(
                response: new Found(),
                headers: ['Location' => '/private']
            );
        } catch (ServiceException $e) {
            unset($_SESSION);
            session_destroy();
            $this->pagesLogger->debug($e->getMessage(), [__CLASS__]);
            return ResponseFactory::createPage(
                response: new Ok(),
                templateName: 'public::login',
                data: ['error' => $e->getMessage(), 'title' => "Login"]
            );
        }
    }
}
