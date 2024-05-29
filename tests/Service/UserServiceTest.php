<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Models\User;

final class UserServiceTest extends BaseServiceTest
{
    public UserService    $userService;
    public UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService    = new UserService($this->userRepository);

        $this->sampleResponse = new User(
            email: 'elon@gmail.com',
            password: 'password',
            firstname: 'Elon',
            lastname: 'Musk',
            privilege: 0
        );
    }

    //INSERT TESTS    
    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->userRepository->method('findById')->willReturn($this->sampleResponse);
        $user = new User('testemail@gmail.com', 'admin', 'Bill', 'Gates', 1);
        $this->userService->save($user);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->userRepository->method('findById')->willReturn($this->sampleResponse);
        $this->assertEquals("Elon", $this->userService->findById("testemail@gmail.com")->firstname);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->userRepository->method('findById')->willReturn(null);
        $this->userService->findById("testemail@gmail.com");
    }

    public function testBadSelectByCredentials(): void
    {
        $this->expectException(ServiceException::class);
        $this->userRepository->method('findById')->willReturn(null);
        $this->userService->findById("testemail@gmail.com");
    }

    //UPDATE TESTS
    public function testBadUpdate(): void
    {
        $this->expectException(ServiceException::class);
        $user = new User('wrong@gmail.com', 'admin', 'Steve', 'Jobs', 0);

        $this->userRepository->method('findFirst')->willReturn(null);
        $this->userService->update($user);
    }

    //DELETE TESTS
    public function testBadDelete(): void
    {
        $this->expectException(ServiceException::class);

        $email = "wrong@gmail.com";
        $this->userRepository->method('findById')->willReturn(null);

        $this->userService->delete($email);
    }
}