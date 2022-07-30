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
    public UserRepository $userRepository;

    public function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);

        $this->userRepository = new UserRepository($this->pdo);               

        $this->sampleObject = [
            "Email"=>'elon@gmail.com',
            "Password"=>'password',
            "firstname"=>'Elon',
            "lastname"=>'Musk',
            "Privilege"=>0,
            "Erased"=>null
        ];
    }

    //INSERT TESTS
    public function testGoodInsertUser():void{                
        $this->sth->method('fetch')->willReturn($this->sampleObject);

        $this->assertEquals($this->userRepository->selectById("elon@gmail.com")->Email,"elon@gmail.com");
    }

    public function testBadInsertUser():void{
        $this->expectException(RepositoryException::class);
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->sth->method('execute')->will($this->throwException(new RepositoryException("")));
        $this->userRepository->insertUser($user);
    }
    
    //SELECT TESTS
    public function testGoodSelectUserById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertNotNull($this->userRepository->selectById("testemail@gmail.com"));
    }
    
    public function testBadSelectUserById(): void
    {
        $this->assertNull($this->userRepository->selectById("wrong@gmail.com"));
    }
    
    public function testGoodSelectUserByCredentials(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertNotNull($this->userRepository->selectByCredentials("testemail@gmail.com","admin"));
    }
    
    public function testBadSelectUserByCredentials(): void
    {
        $this->assertNull($this->userRepository->selectByCredentials("wrong@gmail.com","wrong"));
    }
    
    public function testGoodSelectUserByCredentialsOnlyAdmin(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertNotNull($this->userRepository->selectByCredentials("testemail@gmail.com","admin",true));
    }
    
    public function testBadSelectUserByCredentialsOnlyAdmin(): void
    {   
        $this->assertNull($this->userRepository->selectByCredentials("elon@gmail.com","password",true));
    }
    
    /*
    public function testGoodSelectAll():void{
        $this->pdo->method('query')->willReturn(true);
        $this->sth->method('fetchAll')->willReturn($this->sampleObject);
        $this->assertNotNull($this->userRepository->selectAll());
    }
    
    public function testBadSelectAll():void{
        

        $this->assertNotNull($this->userRepository->selectAll());
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
    */
}
