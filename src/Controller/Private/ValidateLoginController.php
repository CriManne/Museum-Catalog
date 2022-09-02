<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace App\Controller\Private;
session_start();

use App\Controller\ViewsUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class ValidateLoginController extends ViewsUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService) {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $sessionValid = false;

        if(isset($_SESSION['user_email'])){
            $sessionValid = true;
        }

        $credentials = $request->getParsedBody();

        if (!$sessionValid && (!isset($credentials["submitLogin"]) || !isset($credentials["email"]) || !isset($credentials["password"]))) {
            return new HaltResponse(
                400,
                [],
                $this->displayError(400, 'Invalid params!')
            );
        }
        try {
            $user = null;
            if($sessionValid){
                $user = $this->userService->selectById($_SESSION['user_email']);
            }else{
                $user = $this->userService->selectByCredentials($credentials["email"], $credentials["password"]);
            }

            $_SESSION['user_email'] = $user->Email;
            $_SESSION['privilege'] = $user->Privilege;

            return new Response(
                200,
                [],
                $this->plates->render('private::home', ['user' => $user])
            );
        } catch (ServiceException $e) {
            return new HaltResponse(
                404,
                [],
                $this->displayError(404, $e->getMessage())
            );
        }
    }
}
