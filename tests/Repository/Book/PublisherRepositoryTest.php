<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Exception\RepositoryException;
use App\Repository\Book\PublisherRepository;
use App\Model\Book\Publisher;

final class PublisherRepositoryTest extends TestCase
{
    public static PublisherRepository $publisherRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$publisherRepository = new PublisherRepository(self::$pdo);          
    }

    public function setUp():void{
        //Publisher inserted to test duplicated cpu errors
        $publisher= new Publisher(null,'Mondadori');
        self::$publisherRepository->insert($publisher);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE publisher; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $publisher= new Publisher(null,'Einaudi');

        self::$publisherRepository->insert($publisher);

        $this->assertEquals(self::$publisherRepository->selectById(2)->Name,"Einaudi");
    }

    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);        
        $publisher= new Publisher(null,'Mondadori');

        self::$publisherRepository->insert($publisher);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$publisherRepository->selectById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$publisherRepository->selectById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$publisherRepository->selectByName("Mondadori"));
    }
    
    public function testBadSelectByName(): void
    {
        $this->assertNull(self::$publisherRepository->selectByName("WRONG-PUBLISHER-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $publisher1 = new Publisher(null,'P1');
        $publisher2 = new Publisher(null,'P2');
        $publisher3 = new Publisher(null,'P3');
        self::$publisherRepository->insert($publisher1);
        self::$publisherRepository->insert($publisher2);
        self::$publisherRepository->insert($publisher3);
        
        $publishers = self::$publisherRepository->selectAll();
        
        $this->assertEquals(count($publishers),4);
        $this->assertNotNull($publishers[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $publisher= new Publisher(1,"PTEST");
        
        self::$publisherRepository->update($publisher);
        
        $this->assertEquals("PTEST",self::$publisherRepository->selectById(1)->Name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$publisherRepository->delete(1);
        
        $this->assertNull(self::$publisherRepository->selectById(1));
        $this->assertNotNull(self::$publisherRepository->selectById(1,true));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}