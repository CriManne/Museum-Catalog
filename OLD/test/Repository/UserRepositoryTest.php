<?php

    declare(strict_types=1);

    namespace App\Test;

    use PHPUnit\Framework\TestCase;

    use App\Repository\UserRepository;

    class UserRepositoryTest extends TestCase{
        public UserRepository $userRepository;

        public function setUp():void{         
            $this->userRepository = new UserRepository();
        }       
        
        public function testGoodConnection(){
            $userRepository = new UserRepository();      
            $this->assertNotNull($userRepository->pdo);
        }
        
        public function testGetGoodUser(){
            $user = $this->userRepository->getUser('admin@gmail.com','admin');
            $this->assertNotNull($user);
        }
        
        public function testGetBadUser(){
            $user = $this->userRepository->getUser('wrongemail@gmail.com','wrongpsw');
            $this->assertNull($user);
        }

        public function testGetGoodAdminUser(){
            $user = $this->userRepository->getUser('admin@gmail.com','admin',true);
            $this->assertNotNull($user);
        }

        public function testGetBadAdminUser(){
            $user = $this->userRepository->getUser('notadmin@gmail.com','notadmin',true);
            $this->assertNull($user);
        }

        public function tearDown():void{
            unset($this->userRepository);
        }
    }
    
?>

