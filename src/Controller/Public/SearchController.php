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

use App\Exception\ServiceException;
use App\Service\Book\BookService;
use App\Service\Computer\ComputerService;
use App\Service\Magazine\MagazineService;
use App\Service\Peripheral\PeripheralService;
use App\Service\Software\SoftwareService;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class SearchController implements ControllerInterface {

    protected ComputerService $computerService;
    protected PeripheralService $peripheralService;
    protected MagazineService $magazineService;
    protected BookService $bookService;
    protected SoftwareService $softwareService;

    public function __construct(
        ComputerService $computerService,
        PeripheralService $peripheralService,
        MagazineService $magazineService,
        BookService $bookService,
        SoftwareService $softwareService
    ) {
        $this->computerService = $computerService;
        $this->peripheralService = $peripheralService;
        $this->magazineService = $magazineService;
        $this->bookService = $bookService;
        $this->softwareService = $softwareService;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

        $searchResult = [];
        
        $req = $request->getQueryParams();

        if(!isset($req["key"])){
            return new Response(
                404,
                ["Access-Control-Allow-Origin" => "*"],
                json_encode(["message"=>"Invalid request!"])
            );
        }

        $key = $req['key'];

        try {

            if(isset($req['category'])){                
                $searchResult[] = $this->{$req['category']."Service"}->selectByModelName($key);
            }else{
                $searchResult[] = $this->computerService->selectByModelName($key);
                $searchResult[] = $this->peripheralService->selectByModelName($key);
                $searchResult[] = $this->magazineService->selectByTitle($key);
                $searchResult[] = $this->bookService->selectByTitle($key);
                $searchResult[] = $this->softwareService->selectByTitle($key);
            }

        } catch (ServiceException) {}

        return new Response(
            200,
            ["Access-Control-Allow-Origin" => "*"],
            json_encode($searchResult)
        );
    }
}
