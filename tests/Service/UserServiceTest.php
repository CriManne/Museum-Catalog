<?php

declare(strict_types=1);

namespace App\Test\Repository;

use App\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Model\User;
use PDO;
use PDOStatement;

final class UserServiceTest extends TestCase
{
    public UserService $userService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->userRepository = new UserRepository($this->pdo);    
        $this->userService = new UserService($this->userRepository);        

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
        $this->assertEquals($this->userService->selectById("elon@gmail.com")->Email,"elon@gmail.com");        
    }
    
    public function testBadInsertUser():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->userService->insertUser($user);
    }
    
    //SELECT TESTS
    public function testGoodSelectUserById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Elon",$this->userService->selectById("testemail@gmail.com")->firstname);
    }

    public function testBadSelectUserById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->userService->selectById("testemail@gmail.com");
    }

    public function testBadSelectUserByCredentials(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->userService->selectById("testemail@gmail.com");
    }
    
    //UPDATE TESTS
    public function testBadUpdateUser():void{
        $this->expectException(ServiceException::class);
        $user = new User('wrong@gmail.com','admin','Steve','Jobs',0,null);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->userService->updateUser($user);
    }
    
    //DELETE TESTS
    public function testBadDeleteUser():void{
        $this->expectException(ServiceException::class);

        $email = "wrong@gmail.com";
        $this->sth->method('fetch')->willReturn(null);
        
        $this->userService->deleteUser($email);
    }
}