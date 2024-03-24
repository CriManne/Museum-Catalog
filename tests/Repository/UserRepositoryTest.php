<?php
declare(strict_types=1);

namespace App\Test\Repository;

use DI\DependencyException;
use DI\NotFoundException;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use App\Exception\RepositoryException;
use App\Model\User;
use ReflectionException;

/**
 *
 */
final class UserRepositoryTest extends TestCase
{
    /**
     * @var UserRepository
     */
    public static UserRepository $userRepository;
    /**
     * @var PDO|null
     */
    public static ?PDO $pdo;

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$userRepository = new UserRepository(self::$pdo);
    }

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        //User saveed to test duplicated user errors
        $user = new User('testemail@gmail.com', password_hash("admin", PASSWORD_BCRYPT, ['cost' => 11]), 'Bill', 'Gates', 1);
        self::$userRepository->save($user);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        //Clear the user table
        self::$pdo->exec("TRUNCATE TABLE User");
    }

    //INSERT TESTS

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testGoodInsert(): void
    {
        $user = new User('elon@gmail.com', 'password', 'Elon', 'Musk', 0);

        self::$userRepository->save($user);

        $this->assertEquals(self::$userRepository->findById("elon@gmail.com")->email, "elon@gmail.com");
    }

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testBadInsert(): void
    {
        $this->expectException(\AbstractRepo\Exceptions\RepositoryException::class);

        //User already saveed in the setUp() method
        $user = new User('testemail@gmail.com', 'admin', 'Bill', 'Gates', 1);

        self::$userRepository->save($user);
    }

    //SELECT TESTS

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$userRepository->findById("testemail@gmail.com"));
    }

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$userRepository->findById("wrong@gmail.com"));
    }

    /**
     * @return void
     */
    public function testGoodSelectByCredentials(): void
    {
        $this->assertNotNull(self::$userRepository->selectByCredentials("testemail@gmail.com", "admin"));
    }

    /**
     * @return void
     */
    public function testBadSelectByCredentials(): void
    {
        $this->assertNull(self::$userRepository->selectByCredentials("wrong@gmail.com", "wrong"));
    }


    /**
     * @return void
     */
    public function testBadSelectByCredentialsCaseSensitive(): void
    {
        $this->assertNull(self::$userRepository->selectByCredentials("testemail@gmail.com", "ADMIN"));
    }

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testGoodSelectAll(): void
    {
        $user1 = new User('testemail2@gmail.com', 'pwd', 'Bob', 'Dylan', 0);
        $user2 = new User('testemail3@gmail.com', 'pwd', 'Alice', 'Red', 0);
        $user3 = new User('testemail4@gmail.com', 'pwd', 'Tom', 'Green', 0);
        $user4 = new User('testemail5@gmail.com', 'pwd', 'Alice', 'Red', 0);
        $user5 = new User('testemail6@gmail.com', 'pwd', 'Tom', 'Green', 0);
        self::$userRepository->save($user1);
        self::$userRepository->save($user2);
        self::$userRepository->save($user3);
        self::$userRepository->save($user4);
        self::$userRepository->save($user5);

        $users = self::$userRepository->find();

        $this->assertEquals(count($users), 6);
        $this->assertNotNull($users[1]);
    }

    //UPDATE TESTS

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testGoodUpdate(): void
    {
        $user = new User('testemail@gmail.com', 'admin', 'Steve', 'Jobs', 0);

        self::$userRepository->update($user);

        $this->assertEquals("Steve", self::$userRepository->findById("testemail@gmail.com")->firstname);
    }

    //DELETE TESTS

    /**
     * @return void
     * @throws \AbstractRepo\Exceptions\ReflectionException
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws ReflectionException
     */
    public function testGoodDelete(): void
    {
        $email = "testemail@gmail.com";

        self::$userRepository->delete($email);

        $this->assertNull(self::$userRepository->findById("testemail@gmail.com"));
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
