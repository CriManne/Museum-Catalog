<?php
declare(strict_types=1);

namespace App\Test\Repository;

use App\Plugins\Injection\DIC;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use PDO;

class RepositoryTestUtil
{

    /**
     * @return PDO
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getTestPdo(): PDO
    {
        $container = DIC::getContainer();

        $dsn = $container->get('dsn');
        $username = $container->get('username');
        $password = $container->get('psw');

        return new PDO($dsn, $username, $password);
    }

    /**
     * @param PDO $pdo
     * @param string $db_name
     * @return PDO
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function createTestDB(PDO $pdo, string $db_name = 'museum_test'): PDO
    {

        $container = DIC::getContainer();

        $query = "CREATE DATABASE $db_name;";

        $pdo->query($query);

        $pdo->query("USE $db_name;");

        $pdo->exec($container->get('db_dump'));

        return $pdo;
    }

    /**
     * @param PDO $pdo
     * @param string $db_name
     * @return PDO
     */
    public static function dropTestDB(PDO $pdo, string $db_name = 'museum_test'): PDO
    {

        $query = "DROP DATABASE IF EXISTS $db_name;";

        $pdo->query($query);

        return $pdo;
    }

}