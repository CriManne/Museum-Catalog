<?php
declare(strict_types=1);

namespace App\Test\Repository;

use DI\DependencyException;
use DI\NotFoundException;
use PDO;
use PHPUnit\Framework\TestCase;

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
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}