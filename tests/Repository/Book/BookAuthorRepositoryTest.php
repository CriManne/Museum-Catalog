<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Book\BookAuthorRepository;
use App\Model\Book\BookAuthor;

final class BookAuthorRepositoryTest extends TestCase
{
    public static BookAuthorRepository $bookAuthorRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        //TO REMOVE FK CHECKS
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0;");

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$bookAuthorRepository = new BookAuthorRepository(self::$pdo);          
    }

    public function setUp():void{
        //Author inserted to test duplicated cpu errors        
        $bookAuthor= new BookAuthor("BOOK1",1);
        self::$bookAuthorRepository->insert($bookAuthor);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("TRUNCATE TABLE bookauthor;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $bookAuthor= new BookAuthor('BOOK2',1);

        self::$bookAuthorRepository->insert($bookAuthor);
        
        $this->assertEquals(self::$bookAuthorRepository->selectById("BOOK2",1)->BookID,"BOOK2");
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$bookAuthorRepository->selectById("BOOK1",1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$bookAuthorRepository->selectById("BOOK3",1));
    }

    public function testGoodSelectByBookId(): void
    {
        $this->assertEquals(count((array)self::$bookAuthorRepository->selectByBookID("BOOK1")),1);
    }
    
    public function testBadSelectByBookId(): void
    {
        $this->assertNull(self::$bookAuthorRepository->selectByBookID("WRONG BOOK ID"));
    }
    
    public function testGoodSelectByAuthorID(): void
    {        
        $this->assertEquals(count((array)self::$bookAuthorRepository->selectByAuthorID(1)),1);
    }
    
    public function testBadSelectByAuthorID(): void
    {
        $this->assertNull(self::$bookAuthorRepository->selectByAuthorID(3));
    }
    
    
    public function testGoodSelectAll():void{
        $bookAuthor1 = new BookAuthor("BOOK2",5);
        $bookAuthor2 = new BookAuthor("BOOK3",3);
        $bookAuthor3 = new BookAuthor("BOOK4",1);
        self::$bookAuthorRepository->insert($bookAuthor1);
        self::$bookAuthorRepository->insert($bookAuthor2);
        self::$bookAuthorRepository->insert($bookAuthor3);
        
        $bookAuthors = self::$bookAuthorRepository->selectAll();
        
        $this->assertEquals(count((array)$bookAuthors),4);
        $this->assertNotNull($bookAuthors[1]);       
    }
    
    //DELETE TESTS
    public function testGoodDeleteByBookAuthorID():void{       
        
        self::$bookAuthorRepository->deleteById("BOOK1",1);
        
        $this->assertNull(self::$bookAuthorRepository->selectByBookID("BOOK1"));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}