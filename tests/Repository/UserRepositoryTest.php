<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use App\Exception\RepositoryException;
use App\Model\User;
use PDOStatement;

final class UserRepositoryTest extends TestCase
{
    public static UserRepository $userRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$userRepository = new UserRepository(self::$pdo);          
    }

    public function setUp():void{
        //User inserted to test duplicated user errors
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        self::$userRepository->insertUser($user);
    }

    public function tearDown():void{
        //Clear the user table
        self::$pdo->exec("TRUNCATE TABLE User");
    }

    //INSERT TESTS
    public function testGoodInsertUser():void{                
        $user = new User('elon@gmail.com','password','Elon','Musk',0,null);

        self::$userRepository->insertUser($user);

        $this->assertEquals(self::$userRepository->selectById("elon@gmail.com")->Email,"elon@gmail.com");
    }
    public function testBadInsertUser():void{        
        $this->expectException(RepositoryException::class);

        //User already inserted in the setUp() method
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);

        self::$userRepository->insertUser($user);
    }
    
    //SELECT TESTS
    public function testGoodSelectUserById(): void
    {
        $this->assertNotNull(self::$userRepository->selectById("testemail@gmail.com"));
    }
    
    public function testBadSelectUserById(): void
    {
        $this->assertNull(self::$userRepository->selectById("wrong@gmail.com"));
    }

    public function testGoodSelectUserByCredentials(): void
    {
        $this->assertNotNull(self::$userRepository->selectByCredentials("testemail@gmail.com","admin"));
    }
    
    public function testBadSelectUserByCredentials(): void
    {
        $this->assertNull(self::$userRepository->selectByCredentials("wrong@gmail.com","wrong"));
    }
    
    public function testGoodSelectUserByCredentialsOnlyAdminIsAdminTrue(): void
    {
        $this->assertNotNull(self::$userRepository->selectByCredentials("testemail@gmail.com","admin",true));
    }

    public function testGoodSelectUserByCredentialsOnlyAdminIsAdminFalse(): void
    {
        $user = new User('testemail2@gmail.com','pwd','Bob','Dylan',0,null);
        self::$userRepository->insertUser($user);

        $this->assertNotNull(self::$userRepository->selectByCredentials("testemail2@gmail.com","pwd",false));
    }
    
    public function testBadSelectUserByCredentialsOnlyAdmin(): void
    {   
        $this->assertNull(self::$userRepository->selectByCredentials("testemail@gmail.com","admin",false));
    }
    

    public function testGoodSelectAll():void{
        $user1 = new User('testemail2@gmail.com','pwd','Bob','Dylan',0,null);
        $user2 = new User('testemail3@gmail.com','pwd','Alice','Red',0,null);
        $user3 = new User('testemail4@gmail.com','pwd','Tom','Green',0,null);
        self::$userRepository->insertUser($user1);
        self::$userRepository->insertUser($user2);
        self::$userRepository->insertUser($user3);

        $users = self::$userRepository->selectAll();

        $this->assertEquals(count($users),4);
        $this->assertNotNull($users[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdateUser():void{
        $user = new User('testemail@gmail.com','admin','Steve','Jobs',0,null);
        
        self::$userRepository->updateUser($user);
        
        $this->assertEquals("Steve",self::$userRepository->selectById("testemail@gmail.com")->firstname);
    }

    //DELETE TESTS
    public function testGoodDeleteUser():void{
        $email = "testemail@gmail.com";
        
        self::$userRepository->deleteUser($email);
        
        $this->assertNull(self::$userRepository->selectById("testemail@gmail.com"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }
    
}
