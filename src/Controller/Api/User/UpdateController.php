<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Model\User;
use App\Service\UserService;
use App\Util\ORM;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class UpdateController extends ControllerUtil implements ControllerInterface {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        parent::__construct();
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
                $error_message = "Invalid params!";
                $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
                return new HaltResponse(
                    400,
                    [],
                    $this->getResponse($error_message, 400)
                );
            }

            if (isset($params["privilege"])) {
                $params["privilege"] = 1;
            }

            $user = ORM::getNewInstance(User::class, $params);

            $user->Password = password_hash($user->Password, PASSWORD_BCRYPT, [
                'cost' => 11
            ]);

            if ($user->Email !== $_SESSION['user_email']) {
                $error_message = "Unauthorized access!";
                $this->api_log->info($error_message, [__CLASS__, $_SESSION['user_email']]);
                return new HaltResponse(
                    400,
                    [],
                    $this->getResponse($error_message, 400)
                );
            }

            $this->userService->update($user);

            $message = 'User with email {' . $params['Email'] . '} inserted successfully!';
            $this->api_log->info($message, [__CLASS__, $_SESSION['user_email']]);
            return new Response(
                200,
                [],
                $this->getResponse($message)
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(), [__CLASS__, $_SESSION['user_email']]);
            return new HaltResponse(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        }
    }
}
