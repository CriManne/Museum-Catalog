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
        $user = new User('testemail@gmail.com','admin','Bill','Gates',1,null);
        $this->sth->method('execute')->will($this->throwException(new ServiceException("")));
        $this->userService->insertUser($user);
    }
    
    /*
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
*/
}