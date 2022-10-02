<?php

declare(strict_types=1);

namespace App\Controller;

session_start();

use App\Controller\ControllerUtil;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class CheckDBConnectionController extends ControllerUtil implements ControllerInterface {

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        return $response;
    }
}
