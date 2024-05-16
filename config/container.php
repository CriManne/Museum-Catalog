<?php

declare(strict_types=1);

use App\Exception\RepositoryException;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

return [
    'view_path' => 'src/View',
    'artifactImages' => '/public/assets/artifacts/',
    'secureScriptPath' => '/secure_scripts/adv/',
    'basicScriptPath' => '/secure_scripts/basic/',
    Engine::class => function (ContainerInterface $c) {
        $engine = new Engine($c->get('view_path'));
        $engine->addFolder('layouts', $c->get('view_path') . '/layouts');
        $engine->addFolder('private', $c->get('view_path') . '/private');
        $engine->addFolder('artifact_forms', $c->get('view_path') . '/private/artifact/artifact_forms');
        $engine->addFolder('p_artifact', $c->get('view_path') . '/private/artifact');
        $engine->addFolder('p_component', $c->get('view_path') . '/private/component');
        $engine->addFolder('p_user', $c->get('view_path') . '/private/user');

        $engine->addFolder('component_forms', $c->get('view_path') . '/private/component/component_forms');
        $engine->addFolder('reusable', $c->get('view_path') . '/reusable');
        $engine->addFolder('public', $c->get('view_path') . '/public');
        $engine->addFolder('artifact', $c->get('view_path') . '/public/artifact');
        $engine->addFolder('error', $c->get('view_path') . '/error');
        return $engine;
    },
    'dsn' => getenv('DB_DSN'),
    'production_db' => getenv('DB_PROD'),
    'username' => getenv('DB_USERNAME'),
    'psw' => getenv('DB_PASSWORD'),
    'db_dump' => file_get_contents("./sql/create.sql"),
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
    },
    /**
     * This indicates the logging_level that is being used
     * LEVEL 0: minimum logging level, just employee operations and public failed operations (e.g.: artifact not found)
     * LEVEL 1: maximum logging level, every call to the controllers will be logged
     */
    'logging_level'=>1
];
