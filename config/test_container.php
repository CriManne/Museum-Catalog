<?php

declare(strict_types=1);

use App\Exception\DatabaseException;
use App\Plugins\Injection\DIC;
use Psr\Container\ContainerInterface;

return [
    'dsn' => getenv('DB_DSN'),
    'test_db' => getenv('DB_TEST'),
    'username' => getenv('DB_USERNAME'),
    'psw' => getenv('DB_PASSWORD'),
    'db_dump' => file_get_contents("./sql/create.sql"),
    'PDO' => function (ContainerInterface $c) {
        try {
            $pdo = DIC::getPdo();

            if ($pdo)  {
                return $pdo;
            }

            $pdo = new PDO(
                $c->get('dsn') . $c->get('test_db'),
                $c->get('username'),
                $c->get('psw'),
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );

            DIC::setPdo($pdo);

            return $pdo;
        } catch (PDOException) {
            throw new DatabaseException("Cannot connect to database!");
        }
    }
];
