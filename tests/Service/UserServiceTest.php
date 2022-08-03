<?php

declare(strict_types=1);

namespace App\Test\Service;

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
            "Privilege"=>0
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->userService->selectById("elon@gmail.com")->Email,"elon@gmail.com");        
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1);
        $this->userService->insert($user);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Elon",$this->userService->selectById("testemail@gmail.com")->firstname);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->userService->selectById("testemail@gmail.com");
    }

    public function testBadSelectByCredentials(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->userService->selectById("testemail@gmail.com");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $user = new User('wrong@gmail.com','admin','Steve','Jobs',0,null);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->userService->update($user);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);

        $email = "wrong@gmail.com";
        $this->sth->method('fetch')->willReturn(null);
        
        $this->userService->delete($email);
    }
}