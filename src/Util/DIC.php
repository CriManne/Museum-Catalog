<?php

    declare(strict_types=1);

    namespace Mupin\Util;

    use PDO;
    use DI\ContainerBuilder;

    class DIC{

        public static function getPDO(): PDO{
            $builder = new ContainerBuilder();
            $builder->addDefinitions('config/container.php');
            $container = $builder->build();
            $pdo = $container->get('PDO');
            return $pdo;
        }

    }