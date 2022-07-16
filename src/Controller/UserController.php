<?php
declare(strict_types=1);

namespace Mupin\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Mupin\Model\User;
use Mupin\Service\UserService;
use PDO;

class UserController implements ControllerInterface
{
    public UserService $userService;

    public function __construct(PDO $pdo)
    {   
        $this->userService = new UserService($pdo);
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
        $user = new User($request["email"],$request["password"],$request["firstname"],$request["lastname"],intval($request["privilege"]),$request["erased"] ?? null);
    }

    public function putUser($request):void{

    }
    
    public function deleteUser($request):void{

    }
}