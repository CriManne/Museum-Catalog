<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\Images\DeleteController;
use App\Controller\Api\Images\UploadController;
use App\Controller\ControllerUtil;
use App\Exception\ImageUploadException;
use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\SearchEngine\ArtifactSearchEngine;
use App\Service\UserService;
use App\Util\ORM;
use DI\Container;
use DI\ContainerBuilder;
use Exception;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionFunction;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;
use Throwable;
use TypeError;

class CreateController extends ControllerUtil implements ControllerInterface {

    protected Container $container;
    protected ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(ContainerBuilder $builder, ArtifactSearchEngine $artifactSearchEngine) {
        parent::__construct();
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getParsedBody();

        $category = $params["category"] ?? null;

        /**
         * Get the list of artifact's categories
         */
        $categories = ArtifactsListController::$categories;

        /**
         * Return bad request response if no category is set or a wrong one
         */
        if (!$category || !in_array($category, $categories)) {
            $this->api_log->info("Bad request",[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }

        //Repository full path
        $repoPath = "App\\Repository\\$category\\$category" . "Repository";
        //Service full path
        $servicePath = "App\\Service\\$category\\$category" . "Service";

        unset($params["category"]);

        try {
            /**
             * Get service class, throws an exception if not found
             */
            $this->artifactService = $this->container->get($servicePath);

            /**
             * Get repository class, throws an exception if not found
             */
            $this->artifactRepo = $this->container->get($repoPath);


            $instantiatedObject = $this->artifactRepo->returnMappedObject($params);

            $this->artifactService->insert($instantiatedObject);

            //Delete remained old files
            DeleteController::deleteImages($instantiatedObject->ObjectID);

            //Upload new files           
            UploadController::uploadFiles($instantiatedObject->ObjectID, 'images');

            $this->api_log->info("$category inserted successfully!",[__CLASS__,$_SESSION['user_email']]);

            return new Response(
                200,
                [],
                $this->getResponse("$category inserted successfully!")
            );
        } catch (ServiceException $e) {
            $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse($e->getMessage(), 400)
            );
        } catch (Throwable $e) {
            $this->api_log->info($e->getMessage(),[__CLASS__,$_SESSION['user_email']]);
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }
    }
}
