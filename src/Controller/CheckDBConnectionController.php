<?php

declare(strict_types=1);

namespace App\Controller;

session_start();

use App\Controller\ControllerUtil;
use App\Exception\ServiceException;
use App\Service\UserService;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class CheckDBConnectionController extends ControllerUtil implements ControllerInterface {

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        return $response;
    }
}
