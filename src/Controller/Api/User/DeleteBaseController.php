<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\InternalServerError;
use App\Plugins\Http\Responses\Ok;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class DeleteBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected UserService $userService
    )
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();

        $params = $request->getQueryParams();
        try {

            if (!isset($params['id'])) {
                $error_message = "No email set!";
                $this->apiLogger->info($error_message, [__CLASS__, $userEmail]);
                return ResponseFactory::createJson(
                    new BadRequest($error_message)
                );
            }

            $this->userService->delete($params['id']);

            $message = 'User with id {' . $params['id'] . '} deleted!';
            $this->apiLogger->info($message, [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new Ok($message)
            );
        } catch (ServiceException $e) {
            $this->apiLogger->info($e->getMessage(), [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new BadRequest($e->getMessage())
            );
        } catch (RepositoryException $e) {
            $this->apiLogger->error($e->getMessage(), [__CLASS__, $userEmail]);

            return ResponseFactory::createJson(
                new InternalServerError($e->getMessage())
            );
        }
    }
}
