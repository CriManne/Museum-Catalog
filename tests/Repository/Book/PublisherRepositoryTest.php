<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use App\Test\Repository\RepositoryTestUtil;
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
        //Publisher saved to test duplicated cpu errors
        $publisher= new Publisher('Mondadori');
        self::$publisherRepository->save($publisher);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Publisher; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $publisher= new Publisher('Einaudi');

        self::$publisherRepository->save($publisher);

        $this->assertEquals(self::$publisherRepository->findById(2)->name,"Einaudi");
    }

    public function testBadInsert():void{        
        $this->expectException(\AbstractRepo\Exceptions\RepositoryException::class);
        $publisher= new Publisher('Mondadori');

        self::$publisherRepository->save($publisher);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$publisherRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$publisherRepository->findById(3));
    }

    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$publisherRepository->findByQuery("mond"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$publisherRepository->findByQuery("WRONG-PUBLISHER-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $publisher1 = new Publisher('P1');
        $publisher2 = new Publisher('P2');
        $publisher3 = new Publisher('P3');
        self::$publisherRepository->save($publisher1);
        self::$publisherRepository->save($publisher2);
        self::$publisherRepository->save($publisher3);
        
        $publishers = self::$publisherRepository->find();
        
        $this->assertEquals(count($publishers),4);
        $this->assertNotNull($publishers[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $publisher= new Publisher("PTEST",1);
        
        self::$publisherRepository->update($publisher);
        
        $this->assertEquals("PTEST",self::$publisherRepository->findById(1)->name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$publisherRepository->delete(1);
        
        $this->assertNull(self::$publisherRepository->findById(1));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}