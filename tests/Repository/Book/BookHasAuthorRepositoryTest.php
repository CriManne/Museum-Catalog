<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Book\BookHasAuthorRepository;
use App\Model\Book\BookHasAuthor;

final class BookHasAuthorRepositoryTest extends TestCase
{
    public static BookHasAuthorRepository $bookAuthorRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        //TO REMOVE FK CHECKS
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0;");

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$bookAuthorRepository = new BookHasAuthorRepository(self::$pdo);
    }

    public function setUp():void{
        //Author saveed to test duplicated cpu errors        
        $bookAuthor= new BookHasAuthor("BOOK1",1);
        self::$bookAuthorRepository->save($bookAuthor);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("TRUNCATE TABLE BookHasAuthor;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $bookAuthor= new BookHasAuthor('BOOK2',1);

        self::$bookAuthorRepository->save($bookAuthor);
        
        $this->assertEquals(self::$bookAuthorRepository->findById("BOOK2",1)->bookId,"BOOK2");
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$bookAuthorRepository->findById("BOOK1",1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$bookAuthorRepository->findById("BOOK3",1));
    }

    public function testGoodSelectByBookId(): void
    {
        $this->assertEquals(count((array)self::$bookAuthorRepository->findByBookID("BOOK1")),1);
    }
    
    public function testBadSelectByBookId(): void
    {
        $this->assertNull(self::$bookAuthorRepository->findByBookID("WRONG BOOK ID"));
    }
    
    public function testGoodSelectByAuthorID(): void
    {        
        $this->assertEquals(count((array)self::$bookAuthorRepository->findByAuthorID(1)),1);
    }
    
    public function testBadSelectByAuthorID(): void
    {
        $this->assertNull(self::$bookAuthorRepository->findByAuthorID(3));
    }
    
    
    public function testGoodSelectAll():void{
        $bookAuthor1 = new BookHasAuthor("BOOK2",5);
        $bookAuthor2 = new BookHasAuthor("BOOK3",3);
        $bookAuthor3 = new BookHasAuthor("BOOK4",1);
        self::$bookAuthorRepository->save($bookAuthor1);
        self::$bookAuthorRepository->save($bookAuthor2);
        self::$bookAuthorRepository->save($bookAuthor3);
        
        $bookAuthors = self::$bookAuthorRepository->find();
        
        $this->assertEquals(count((array)$bookAuthors),4);
        $this->assertNotNull($bookAuthors[1]);       
    }
    
    //DELETE TESTS
    public function testGoodDeleteByBookAuthorID():void{
        
        self::$bookAuthorRepository->deleteById("BOOK1",1);
        
        $this->assertNull(self::$bookAuthorRepository->findByBookID("BOOK1"));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}