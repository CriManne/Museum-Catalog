<?php
declare(strict_types=1);

namespace Mupin\Test\Repository;

use PHPUnit\Framework\TestCase;
use Mupin\Repository\UserRepository;
use DI\ContainerBuilder;

final class UserRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions('config/container.php');
        $container = $builder->build();
        $pdo = $container->get('PDO');

        $this->userRepository = new UserRepository($pdo);      
        $this->userRepository->pdo->beginTransaction();  
    }

    public function testGoodSelectUserById(): void
    {
        $this->assertNotNull($this->userRepository->selectById("admin@gmail.com"));
    }

    public function testBadSelectUserById(): void
    {
        $this->assertNull($this->userRepository->selectById("wrong@gmail.com"));
    }

    public function testGoodSelectUserByCredentials(): void
    {
        $this->assertNotNull($this->userRepository->selectByCredentials("admin@gmail.com","admin"));
    }

    public function testBadSelectUserByCredentials(): void
    {
        $this->assertNull($this->userRepository->selectByCredentials("wrong@gmail.com","wrong"));
    }

    public function testGoodSelectUserByCredentialsOnlyAdmin(): void
    {
        $this->assertNotNull($this->userRepository->selectByCredentials("admin@gmail.com","admin",true));
    }

    public function testBadSelectUserByCredentialsOnlyAdmin(): void
    {
        $this->assertNull($this->userRepository->selectByCredentials("notadmin@gmail.com","notadmin",true));
    }

    public function tearDown():void{
        $this->userRepository->pdo->rollBack();
    }
}
