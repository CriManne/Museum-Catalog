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

class UsersController extends ViewsUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(Engine $plates, UserService $userService) {
        parent::__construct($plates);
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {                
        try {
            if(!isset($_SESSION['user_email'])){
                throw new ServiceException("Unauthorized");
            }
            $users = $this->userService->selectAll();
            return new Response(
                200,
                ["Access-Control-Allow-Origin"=>"*"],
                json_encode($users)
            );
        } catch (ServiceException $e) {
            return new HaltResponse(
                400,
                ["Access-Control-Allow-Origin"=>"*"],
                $e->getMessage()
            );
        }
    }
}
