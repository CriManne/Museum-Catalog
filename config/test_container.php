<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

use App\Exception\RepositoryException;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

return [
    'dsn' => 'mysql:host=localhost;',
    'test_db' => 'dbname=mupin_test;',
    'username' => 'root',
    'psw' => '',
    'PDO' => function (ContainerInterface $c) {
        try {
            return new PDO(
                $c->get('dsn') . $c->get('test_db'),
                $c->get('username'),
                $c->get('psw'),
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException) {
            throw new RepositoryException("Cannot connect to database!");
        }
    }
];
