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
    'view_path' => 'src/View',
    Engine::class => function (ContainerInterface $c) {
        $engine = new Engine($c->get('view_path'));
        $engine->addFolder('layouts', $c->get('view_path') . '/layouts');
        $engine->addFolder('private', $c->get('view_path') . '/private');
        $engine->addFolder('p_admin', $c->get('view_path') . '/private/admin');
        $engine->addFolder('reusable', $c->get('view_path') . '/reusable');
        $engine->addFolder('public', $c->get('view_path') . '/public');
        $engine->addFolder('artifact', $c->get('view_path') . '/public/artifact');
        $engine->addFolder('error', $c->get('view_path') . '/error');
        return $engine;
    },
    'authentication' => [
        'username' => 'test',
        'password' => 'password'
    ],
    'dsn' => 'mysql:host=localhost;',
    'production_db' => 'dbname=mupin;',
    'username' => 'root',
    'db_dump' => file_get_contents("./sql/create_mupin.sql"),
    'psw' => '',
    'PDO' => function (ContainerInterface $c) {
        try {
            return new PDO(
                $c->get('dsn') . $c->get('production_db'),
                $c->get('username'),
                $c->get('psw'),
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException) {
            throw new RepositoryException("Cannot connect to database!");
        }
    }
];
