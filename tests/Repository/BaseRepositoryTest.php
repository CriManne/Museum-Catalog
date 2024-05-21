<?php
declare(strict_types=1);

namespace App\Test\Repository;

use App\Plugins\Injection\DIC;
use DI\DependencyException;
use DI\NotFoundException;
use PDO;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

abstract class BaseRepositoryTest extends TestCase
{
    public static ?PDO $pdo;

    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        $dic = new ReflectionClass(DIC::class);
        $dic->setStaticPropertyValue('container', null);
        $dic->setStaticPropertyValue('containerPath', DIC::TEST_CONTAINER_PATH);
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }    
}