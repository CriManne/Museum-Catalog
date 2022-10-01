<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\ControllerUtil;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Util\ORM;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class PostController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        try {
            $params = $request->getParsedBody();

            if (
                !isset($params['Email']) ||
                !isset($params['Password']) ||
                !isset($params['firstname']) ||
                !isset($params['lastname'])
            ) {
                return new HaltResponse(
                    400,
                    [],
                    $this->getResponse("Bad request!", 400)
                );
            }

            if (isset($params["Privilege"])) {
                $params["Privilege"] = 1;
            }

            $user = ORM::getNewInstance(User::class, $params);
            $this->userService->insert($user);

            return new Response(
                200,
                [],
                $this->getResponse('User with email {' . $params['Email'] . '} inserted successfully!')
            );
        } catch (ServiceException $e) {
            return new HaltResponse(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        }
    }
}
