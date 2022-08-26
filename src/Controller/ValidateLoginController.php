<?php
/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace App\Controller;

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

class ValidateLoginController implements ControllerInterface
{
    protected Engine $plates;

    protected UserService $userService;
    
    public function __construct(Engine $plates, UserService $userService)
    {
        $this->plates = $plates;
        $this->userService = $userService;        
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $credentials = $request->getParsedBody();

        if(!isset($credentials["submitLogin"]) || !isset($credentials["email"]) || !isset($credentials["password"])){            
            return new Response(
                400,
                [],
                "Bad request!"
            );
        }

        try{
            $user = $this->userService->selectByCredentials($credentials["email"],$credentials["password"]);
            $request = $request->withAttribute('user',$user);   
            var_dump($request->getAttributes()); //--> returns array(1)  'user' => object(User)....
            var_dump($request->getAttribute('user')); //--> returns object(User) ....
            return $response;
        }catch(ServiceException $e){
            return new HaltResponse(
                404,
                [],
                $e->getMessage()
            );
        }
    }
}
