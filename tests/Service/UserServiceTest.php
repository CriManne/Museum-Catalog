<?php

declare(strict_types=1);

namespace App\Test\Repository;

use App\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use DI\ContainerBuilder;
use App\Service\UserService;
use App\Util\DIC;
use App\Model\User;

final class UserServiceTest extends TestCase
{
    public UserService $userService;

    public function setUp(): void
    {        
        $this->userService = DIC::getContainer()->get(UserService::class);
        $this->userService->userRepository->pdo->beginTransaction();          

        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->userService->insertUser($user);
    }

    //INSERT TESTS
    public function testGoodInsertUser():void{
        $user = new User('elon@gmail.com','password','Elon','Musk',0,null);

        $this->userService->insertUser($user);

        $this->assertEquals($this->userService->selectById("elon@gmail.com")->email,"elon@gmail.com");
    }

    public function testBadInsertUser():void{
        $this->expectException(ServiceException::class);
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->userService->insertUser($user);
    }
    
    //READ TESTS
    public function testGoodSelectUserById(): void
    {
        $this->assertNotNull($this->userService->selectById("testemail@gmail.com"));
    }

    public function testBadSelectUserById(): void
    {
        $this->assertNull($this->userService->selectById("wrong@gmail.com"));
    }

    public function testGoodSelectUserByCredentials(): void
    {
        $this->assertNotNull($this->userService->selectByCredentials("testemail@gmail.com","admin"));
    }

    public function testBadSelectUserByCredentials(): void
    {
        $this->assertNull($this->userService->selectByCredentials("wrong@gmail.com","wrong"));
    }

    public function testGoodSelectUserByCredentialsOnlyAdmin(): void
    {
        $this->assertNotNull($this->userService->selectByCredentials("testemail@gmail.com","admin",true));
    }

    public function testBadSelectUserByCredentialsOnlyAdmin(): void
    {        
        $user = new User('elon@gmail.com','password','Elon','Musk',0,null);
        $this->userService->insertUser($user);
        $this->assertNull($this->userService->selectByCredentials("elon@gmail.com","password",true));
    }

    
    //UPDATE TESTS
    public function testGoodUpdateUser():void{
        $user = new User('testemail@gmail.com','admin','Steve','Jobs',0,null);

        $this->userService->updateUser($user);
        
        $this->assertEquals("Steve",$this->userService->selectById("testemail@gmail.com")->firstname);
    }

    public function testBadUpdateUser():void{
        $this->expectException(ServiceException::class);

        $user = new User('wrong@gmail.com','admin','Steve','Jobs',0,null);

        $this->userService->updateUser($user);
    }
    
    //DELETE TESTS
    public function testGoodDeleteUser():void{
        $email = "testemail@gmail.com";
        
        $this->userService->deleteUser($email);
        
        $this->assertNull($this->userService->selectById("testemail@gmail.com"));
    }

    public function testBadDeleteUser():void{
        $this->expectException(ServiceException::class);

        $email = "wrong@gmail.com";
        
        $this->userService->deleteUser($email);
    }


    public function tearDown():void{
        $this->userService->userRepository->pdo->rollBack();
    }
}