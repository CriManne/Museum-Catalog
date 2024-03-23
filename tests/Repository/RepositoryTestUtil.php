<?php
    declare(strict_types=1);

    namespace App\Test\Repository;

    use App\Util\DIC;
    use PDO;

    class RepositoryTestUtil{

        public static function getTestPdo(): PDO{

            $container = DIC::getContainer();

            $dsn = $container->get('dsn');
            $username = $container->get('username');
            $password = $container->get('psw');

            return new PDO($dsn,$username,$password);
        }

        public static function createTestDB(PDO $pdo,string $db_name='museum_test'): PDO{

            $container = DIC::getContainer();

            $query = "CREATE DATABASE $db_name;";

            $pdo->query($query);

            $pdo->query("USE $db_name;");

            $pdo->exec($container->get('db_dump'));
            
            return $pdo;
        }

        public static function dropTestDB(PDO $pdo,string $db_name='museum_test'): PDO{

            $query = "DROP DATABASE IF EXISTS $db_name;";

            $pdo->query($query);

            return $pdo;           
        }

    }