<?php

declare(strict_types=1);

namespace App\Controller\Pages\Private\Component;

use AbstractRepo\Exceptions\RepositoryException;
use App\Controller\Api\ComponentsListController;
use App\Controller\BaseController;
use App\Exception\ServiceException;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\NotFound;
use App\Plugins\Http\Responses\Ok;
use App\SearchEngine\ComponentSearchEngine;
use App\Service\UserService;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;


class UpdateBaseController extends BaseController implements ControllerInterface
{
    public function __construct(
        protected UserService           $userService,
        protected ComponentSearchEngine $componentSearchEngine
    )
    {
        parent::__construct();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     * @throws RepositoryException
     * @throws ServiceException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userEmail = $this->getLoggedUserEmail();
        $params    = $request->getQueryParams();

        $category = $params["category"] ?? null;
        $id       = $params["id"] ?? null;

        $error_message = null;

        if (!$category) {
            $error_message = "No category set!";
        } else if (!$id) {
            $error_message = "No id set!";
        }

        if ($error_message) {
            $this->pagesLogger->info($error_message, [__CLASS__, $userEmail]);
            return ResponseFactory::createErrorPage(new BadRequest($error_message));
        }

        if (!in_array($category, ComponentsListController::CATEGORIES)) {
            $error_message = "Category not found!";
            $this->pagesLogger->info($error_message, [__CLASS__, $userEmail]);
            return ResponseFactory::createErrorPage(new NotFound($error_message));
        }

        $user = $this->userService->findById($userEmail);

        $this->pagesLogger->info("Successful get page", [__CLASS__, $userEmail]);
        return ResponseFactory::createPage(
            response: new Ok(),
            templateName: "component_forms::$category",
            data: ['user' => $user, 'title' => "Update $category"]
        );
    }
}
