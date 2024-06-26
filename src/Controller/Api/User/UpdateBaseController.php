<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\BaseController;
use App\Models\User;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Ok;
use App\Service\UserService;
use App\Util\ORM;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class UpdateBaseController extends BaseController implements ControllerInterface
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
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->apiHandler(
            function () use ($request, $response) {
                $userEmail = $this->getLoggedUserEmail();

                $params = $request->getParsedBody();

                if (
                    !isset($params['email'],
                        $params['password'],
                        $params['firstname'],
                        $params['lastname'],
                        $params["privilege"]
                    )
                ) {
                    $error_message = "Invalid params!";
                    $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);
                    return ResponseFactory::createJson(
                        new BadRequest($error_message)
                    );
                }

                /**
                 * @var User $user
                 */
                $user = ORM::getNewInstance(User::class, $params);

                if ($user->email !== $userEmail) {
                    $error_message = "Unauthorized access!";
                    $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);
                    return ResponseFactory::createJson(
                        new BadRequest($error_message)
                    );
                }

                $this->userService->update($user);

                $message = 'User with email {' . $params['email'] . '} saved successfully!';
                $this->apiLogger->info($message, [__CLASS__, $userEmail]);
                return ResponseFactory::createJson(
                    new Ok($message)
                );
            }
        );
    }
}
