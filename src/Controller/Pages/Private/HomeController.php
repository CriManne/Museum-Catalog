<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private;

use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Service\UserService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class HomeController extends ControllerUtil implements ControllerInterface {
    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService) {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    /**
     * @throws RepositoryException
     * @throws \ReflectionException
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        if (isset($request->getQueryParams()['logout-btn'])) {
            if(!$this->is_session_started()){
                session_start();
            }
            unset($_SESSION);
            session_destroy();
            return new Response(
                302,
                ['Location' => '/login']
            );
        }

        $user = $this->userService->findById($_SESSION['user_email']);

        $this->pages_log->info("Successfull get page", [__CLASS__, $_SESSION['user_email']]);
        return new Response(
            200,
            [],
            $this->plates->render('private::home', ['title' => "Dashboard user", 'user' => $user])
        );
    }

    public function is_session_started(): bool
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
}
