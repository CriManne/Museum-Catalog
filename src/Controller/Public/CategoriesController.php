<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);
namespace App\Controller\Public;
session_start();


use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class CategoriesController implements ControllerInterface {    

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        
        
        $categories = [
            'Computer',
            'Peripheral',
            'Book',
            'Magazine',
            'Software'            
        ];
        
        return new Response(
            200,
            ["Access-Control-Allow-Origin"=>"*"],
            json_encode($categories)
        );
    }
}