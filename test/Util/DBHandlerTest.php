<?php

    declare(strict_types=1);

    namespace App\Test;

    use PHPUnit\Framework\TestCase;

    use App\Util\DBHandler;
    use PDOException;

    class DBHandlerTest extends TestCase{
        public DBHandler $dbHandler;

        public function setUp():void{         
            $this->dbHandler = new DBHandler();
        }       
        
        public function testGoodConnection(){
            $dbHandler = new DBHandler();      
            $this->assertNotNull($dbHandler->pdo);
        }
        
        public function testGetGoodUser(){
            $user = $this->dbHandler->getUser('admin@gmail.com','admin');
            $this->assertNotNull($user);
        }
        
        public function testGetBadUser(){
            $user = $this->dbHandler->getUser('wrongemail@gmail.com','wrongpsw');
            $this->assertNull($user);
        }

        public function tearDown():void{
            unset($_SERVER['DOCUMENT_ROOT']);
            unset($this->dbHandler);
        }
    }
    
?>

