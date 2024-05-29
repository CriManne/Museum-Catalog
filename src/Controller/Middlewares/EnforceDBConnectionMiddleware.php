<?php

declare(strict_types=1);

namespace App\Controller\Middlewares;

use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

/**
 * Controller used as middleware to check the db connection in those
 * controllers that aren't using a db connected class
 */
class EnforceDBConnectionMiddleware implements ControllerInterface
{
    /**
     * PDO object
     * @var PDO
     */
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
