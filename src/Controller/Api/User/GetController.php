<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\ControllerUtil;
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

class GetController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {            
            $users = $this->userService->selectAll();

            return new Response(
                200,
                [],
                json_encode($users)
            );
        } catch (ServiceException $e) {
            return new HaltResponse(
                400,
                [],
                $this->getResponse($e->getMessage(),400)
            );
        }
    }
}
