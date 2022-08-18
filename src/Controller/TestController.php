<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use Psr\Http\Message\ServerRequestInterface;
use App\Repository\Book\AuthorRepository;
use SimpleMVC\Controller\ControllerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use PDO;

class TestController implements ControllerInterface
{
    public AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {   
        $this->authorRepository = $authorRepository;
    }


    public function execute(ServerRequestInterface $request,ResponseInterface $response):ResponseInterface
    {
        $params = $request->getQueryParams();

        //$author = $this->authorRepository->selectById(intval($params["AuthorID"]));       
        $authors = $this->authorRepository->selectAll();

        if($authors){               
            return new Response(
                200, 
                [],
                json_encode($authors)
            );
        }
        
        return new Response(
            404, 
            [],
            "Author not found"
        );
    }   
}