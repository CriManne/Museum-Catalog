<?php
declare(strict_types=1);

namespace Mupin\Controller;

use Mupin\Exceptions\RepositoryException;
use Mupin\Exceptions\ServiceException;
use Psr\Http\Message\ServerRequestInterface;
use Mupin\Model\User;
use Mupin\Service\UserService;
use PDO;

class UserController implements ControllerInterface
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {   
        $this->userService = $userService;
    }


    public function execute(ServerRequestInterface $request)
    {
        switch($request->getMethod()){
            case "GET":{
                $this->getUser($request);
                break;
            }
            case "POST":{
                $this->postUser($request);
                break;
            }
            case "PUT":{
                $this->putUser($request);
                break;
            }
            case "DELETE":{
                $this->deleteUser($request);
                break;
            }
        }
    }

    public function getUser(ServerRequestInterface $request):void{
        $params = $request->getQueryParams();

        $user = null;

        if(!isset($params["password"])){
            $user = $this->userService->selectById($params["email"]);
        }else{
            $user = $this->userService->selectByCredentials($params["email"],$params["password"],boolval($params["isAdmin"] ?? false));
        }

        if($user == null){
            http_response_code(404);
            echo "User not found";
            return;
        }

        http_response_code(200);
        echo json_encode($user);        
    }

    public function postUser($request):void{        
        $params = $request->getParsedBody();
        $user = new User($params["email"],$params["password"],$params["firstname"],$params["lastname"],intval($params["privilege"]),$params["erased"] ?? null);
        
        //TO REVIEW
        try{
            $this->userService->insertUser($user);
            http_response_code(200);
            echo json_encode($user); 
        }catch(ServiceException $e){
            http_response_code(400);
            echo $e->getMessage();
        }catch(RepositoryException $e){
            http_response_code(500);
            echo $e->getMessage();
        }        
    }

    public function putUser($request):void{

    }
    
    public function deleteUser($request):void{

    }
}