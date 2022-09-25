<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\ControllerUtil;
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

class PostController extends ControllerUtil implements ControllerInterface {

    protected PDO $pdo;
    protected Container $container;
    protected ArtifactSearchEngine $artifactSearchEngine;

    public function __construct(PDO $pdo, ContainerBuilder $builder,ArtifactSearchEngine $artifactSearchEngine) {
        $this->pdo = $pdo;
        $builder->addDefinitions('config/container.php');
        $this->container = $builder->build();
        $this->artifactSearchEngine = $artifactSearchEngine;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $params = $request->getParsedBody();
        
        $method = $request->getMethod();

        $category = $params["category"] ?? null;

        if (!$category) {
            return new Response(
                400,
                [],
                $this->getResponse("Bad request!", 400)
            );
        }

        $categories = ArtifactsListController::$categories;

        foreach ($categories as $singleCategory) {
            try {
                //Service full path
                $servicePath = "App\\Service\\$singleCategory\\$category" . "Service";
                //Class full path
                $classPath = "App\\Model\\$singleCategory\\$category";

                /**
                 * Get service class, throws an exception if not found
                 */
                $this->artifactService = $this->container->get($servicePath);

                unset($params["category"]);
                $rawObject = $params;

                $instantiatedObject = null;
                if (in_array(ucwords($category), $categories)) {
                    //Repository full path
                    $repoPath = "App\\Repository\\$singleCategory\\$category" . "Repository";

                    /**
                     * Get service class, throws an exception if not found
                     */
                    $this->artifactRepo = $this->container->get($repoPath);

                    $instantiatedObject = $this->artifactRepo->returnMappedObject($rawObject);
                } else {
                    $instantiatedObject = ORM::getNewInstance($classPath, $rawObject);
                }

                if($method=="POST"){
                    try{
                        $this->artifactSearchEngine->selectById($instantiatedObject->ObjectID);

                        return new Response(
                            400,
                            [],
                            $this->getResponse("Object id already used!",400)
                        );
                    }catch(ServiceException){}

                    $this->artifactService->insert($instantiatedObject);

                    //Delete remained old files
                    self::deleteFiles($instantiatedObject->ObjectID);

                    //Upload new files
                    //try{
                        $this->uploadFiles($instantiatedObject->ObjectID,'images');
                    // }catch(Exception $e){
                    //     return new Response(
                    //         400,
                    //         [],
                    //         $this->getResponse("Error while uploading the images ".$e->getMessage()."! The $category is successfully inserted.",400)
                    //     );    
                    // }
                    return new Response(
                        200,
                        [],
                        $this->getResponse("$category inserted successfully!")
                    );
                }else{
                    $this->artifactService->update($instantiatedObject);
                    return new Response(
                        200,
                        [],
                        $this->getResponse("$category updated successfully!")
                    );
                }
            } catch (ServiceException $e) {
                return new Response(
                    400,
                    [],
                    $this->getResponse($e->getMessage(), 400)
                );
            } catch (Throwable | Exception) {
            }
        }

        return new Response(
            400,
            [],
            $this->getResponse("Bad request!", 400)
        );
    }

    public static function deleteFiles(string $ObjectID){

        $dir =$_SERVER['DOCUMENT_ROOT']."/assets/artifacts/";

        $files = scandir($dir);

        $regex = '/^'.$ObjectID.'_\d/';

        $files = array_map('strval', preg_filter('/^/', $dir, preg_grep($regex, $files)));

        foreach($files as $file){
            unlink($file);
        }
    }

    public function uploadFiles(string $ObjectID,string $name){

        $files = $_FILES[$name];

        // if(isset($files['error'])){
        //     throw new Exception();
        // }  

        $path = $_SERVER['DOCUMENT_ROOT']."/assets/artifacts/";

        $index = 1;

        foreach($files['tmp_name'] as $tmp_name){  
            $splittedName = explode('.',$files['name'][$index-1]);
            $fileextension = end($splittedName);          
            $filename = $path.$ObjectID."_".$index.".".$fileextension;
            move_uploaded_file($tmp_name,$filename);
            $index++;
        }
    }
}
