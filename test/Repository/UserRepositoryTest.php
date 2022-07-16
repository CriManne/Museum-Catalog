<?php
declare(strict_types=1);

namespace Mupin\Test\Repository;

use PHPUnit\Framework\TestCase;
use Mupin\Repository\UserRepository;
use Mupin\Util\DIC;
use Mupin\Exceptions\RepositoryException;
use Mupin\Model\User;

final class UserRepositoryTest extends TestCase
{
    public UserRepository $userRepository;

    public function setUp(): void
    {
        $pdo = DIC::getPDO();

        $this->userRepository = new UserRepository($pdo);      
        $this->userRepository->pdo->beginTransaction();  

        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->userRepository->insertUser($user);
    }

    //INSERT TESTS
    public function testGoodInsertUser():void{
        $user = new User('elon@gmail.com','password','Elon','Musk',0,null);

        $this->userRepository->insertUser($user);

        $this->assertEquals($this->userRepository->selectById("elon@gmail.com")->email,"elon@gmail.com");
    }

    public function testBadInsertUser():void{
        $this->expectException(RepositoryException::class);
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->userRepository->insertUser($user);
    }
    
    //SELECT TESTS
    public function testGoodSelectUserById(): void
    {
        $this->assertNotNull($this->userRepository->selectById("testemail@gmail.com"));
    }

    public function testBadSelectUserById(): void
    {
        $this->assertNull($this->userRepository->selectById("wrong@gmail.com"));
    }

    public function testGoodSelectUserByCredentials(): void
    {
        $this->assertNotNull($this->userRepository->selectByCredentials("testemail@gmail.com","admin"));
    }

    public function testBadSelectUserByCredentials(): void
    {
        $this->assertNull($this->userRepository->selectByCredentials("wrong@gmail.com","wrong"));
    }

    public function testGoodSelectUserByCredentialsOnlyAdmin(): void
    {
        $this->assertNotNull($this->userRepository->selectByCredentials("testemail@gmail.com","admin",true));
    }

    public function testBadSelectUserByCredentialsOnlyAdmin(): void
    {        
        $user = new User('elon@gmail.com','password','Elon','Musk',0,null);
        $this->userRepository->insertUser($user);
        $this->assertNull($this->userRepository->selectByCredentials("elon@gmail.com","password",true));
    }

    
    //UPDATE TESTS
    public function testGoodUpdateUser():void{
        $user = new User('testemail@gmail.com','admin','Steve','Jobs',0,null);

        $this->userRepository->updateUser($user);
        
        $this->assertEquals("Steve",$this->userRepository->selectById("testemail@gmail.com")->firstname);
    }
    
    //DELETE TESTS
    public function testGoodDeleteUser():void{
        $email = "testemail@gmail.com";
        
        $this->userRepository->deleteUser($email);
        
        $this->assertNull($this->userRepository->selectById("testemail@gmail.com"));
    }


    public function tearDown():void{
        $this->userRepository->pdo->rollBack();
    }
}
