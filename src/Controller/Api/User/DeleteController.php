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

class DeleteController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $params = $request->getQueryParams();            

            if(!isset($params['id'])){
                return new Response(
                    400,
                    [],
                    $this->getResponse("Invalid request!",400)
                );
            }

            $this->userService->delete($params['id']);

            return new Response(
                200,
                [],
                $this->getResponse('User with id {'.$params['id'].'} deleted!')
            );
        } catch (ServiceException $e) {
            return new Response(
                400,
                [],
                $this->getResponse($e->getMessage(),400)
            );
        }
    }
}
