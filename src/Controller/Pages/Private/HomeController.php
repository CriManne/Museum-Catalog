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

use App\Exception\ServiceException;
use App\Service\UserService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class HomeController implements ControllerInterface {
    protected Engine $plates;
    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService) {
        $this->plates = $plates;
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try{

            if(isset($request->getQueryParams()['logout-btn'])){
                unset($_SESSION);
                session_destroy();
                return new Response(
                    302,
                    ['Location'=>'/login']
                );
            }

            $user = $this->userService->selectById($_SESSION['user_email']);

            return new Response(
                200,
                [],
                $this->plates->render('private::home',['user'=>$user])
            );
        }catch(ServiceException){

        }
    }
}
