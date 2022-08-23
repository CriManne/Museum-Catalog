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

use App\Model\User;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class PRIV_HomeController implements ControllerInterface
{
    protected Engine $plates;
    protected array $user;
    
    public function __construct(Engine $plates,array $user)
    {
        $this->plates = $plates;
        $this->user = $user;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {        
        var_dump($this->user);
        return new Response(
            200,
            [],
            $this->plates->render('P_Home')
        );
    }
}
