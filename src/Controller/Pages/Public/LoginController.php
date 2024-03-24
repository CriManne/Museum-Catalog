<?php

declare(strict_types=1);

namespace App\Controller\Pages\Public;

use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Service\UserService;
use DI\DependencyException;
use DI\NotFoundException;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class LoginController extends ControllerUtil implements ControllerInterface {

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
        $sessionValid = false;

        if(!$this->is_session_started()){
            session_start();
        }

        if(isset($_SESSION['user_email'])){
            $sessionValid = true;
        }

        $credentials = $request->getParsedBody();
        
        if (!$sessionValid && (!isset($credentials["submitLogin"]) || !isset($credentials["email"]) || !isset($credentials["password"]))) {
            if ($this->container->get('logging_level') === 1) {
                $this->pages_log->info("Successfull get page", [__CLASS__]);
            }
            return new Response(
                200,
                [],
                $this->plates->render('public::login',['title'=>"Login"])
            );
        }
        try {
            $user = null;

            if($sessionValid){
                $user = $this->userService->selectById($_SESSION['user_email']);
            }else{
                $user = $this->userService->selectByCredentials($credentials["email"], $credentials["password"]);
            }

            $_SESSION['user_email'] = $user->email;
            $_SESSION['privilege'] = $user->privilege;
            
            return new Response(
                302,
                ['Location'=>'/private']                
            );
        } catch (ServiceException $e) {            
            unset($_SESSION);
            session_destroy();
            if ($this->container->get('logging_level') === 1) {
                $this->pages_log->info($e->getMessage(), [__CLASS__]);
            }
            return new Response(
                200,
                [],
                $this->plates->render('public::login',['error'=>$e->getMessage(),'title'=>"Login"])
            );
        }
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
