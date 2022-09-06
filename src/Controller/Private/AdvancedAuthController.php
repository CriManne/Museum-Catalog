<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);
namespace App\Controller\Private;

use App\Controller\ControllerUtil;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class AdvancedAuthController extends ControllerUtil implements ControllerInterface {    

    public function __construct(Engine $plates) {
        parent::__construct($plates);
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        
        if(!isset($_SESSION['privilege']) || $_SESSION['privilege']!==1){
            return new HaltResponse(
                401,
                [],
                $this->displayError(401,"Unauthorized access")   
            );
        }
        return $response;
    }
}
