<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;

use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\Book\AuthorRepository;
use App\Repository\Book\BookAuthorRepository;

use App\Exception\RepositoryException;
use App\Model\Book\Author;
use App\Model\Book\Book;
use App\Model\Book\Publisher;

final class BookRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static Author $sampleAuthor;
    public static Book $sampleBook;
    public static Publisher $samplePublisher;

    public static PublisherRepository $publisherRepository;
    public static AuthorRepository $authorRepository;
    public static BookAuthorRepository $bookAuthorRepository;
    public static BookRepository $bookRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        // Repository to handle relations
        self::$authorRepository = new AuthorRepository(self::$pdo);
        self::$bookAuthorRepository = new BookAuthorRepository(self::$pdo);
        self::$publisherRepository = new PublisherRepository(self::$pdo);


        // Repository to handle book
        self::$bookRepository = new BookRepository(
            self::$pdo,
            self::$publisherRepository,            
            self::$authorRepository,
            self::$bookAuthorRepository
        );        
        
        self::$sampleAuthor = new Author(            
            "George",
            "Orwell",
            1
        );

        self::$samplePublisher = new Publisher(
            'Einaudi',
            1
        );              

        self::$sampleBook = new Book(
            "objID",
            "1984",
            self::$samplePublisher,
            1984,
            [self::$sampleAuthor],
            null,
            null,
            null,
            "AAABBBCCC",
            95,
        );

        self::$authorRepository->insert(self::$sampleAuthor);
        self::$publisherRepository->insert(self::$samplePublisher);
    }

    public function setUp():void{
        //Book inserted to test duplicated supports errors
        self::$bookRepository->insert(self::$sampleBook);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE book; TRUNCATE TABLE GenericObject; TRUNCATE TABLE BookAuthor; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $book = clone self::$sampleBook;
        $book->objectId = "objID2";
        $book->title = "2001";
        
        self::$bookRepository->insert($book);

        $this->assertEquals(self::$bookRepository->selectById("objID2")->title,"2001");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);
        //Book already inserted in the setUp() method  
        self::$bookRepository->insert(self::$sampleBook);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$bookRepository->selectById("objID"));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$bookRepository->selectById("WRONGID"));
    }       
    
    public function testGoodSelectAll():void{
        $book1 = clone self::$sampleBook;
        $book1->objectId = "objID1";
        
        $book2 = clone self::$sampleBook;
        $book2->objectId = "objID2";
        
        $book3 = clone self::$sampleBook;
        $book3->objectId = "objID3";
                
        self::$bookRepository->insert($book1);
        self::$bookRepository->insert($book2);
        self::$bookRepository->insert($book3);
        
        $books = self::$bookRepository->selectAll();
        
        $this->assertEquals(count($books),4);
        $this->assertNotNull($books[1]);       
    } 
    
    public function testGoodSelectBytitle():void{

        $book = clone self::$sampleBook;
        $book->objectId = "objID2";
        $book->title = "Big Bang";
        
        self::$bookRepository->insert($book);

        $this->assertEquals(self::$bookRepository->selectBytitle("Big Bang")->title,"Big Bang");
    }

    public function testGoodSelectByKey(): void {

        $book = clone self::$sampleBook;
        $book->objectId = "objID2";
        $book->title = "Big Bang";

        self::$bookRepository->insert($book);

        $this->assertEquals(count(self::$bookRepository->selectByKey("gEoRge")),2);
    }

    //UPDATE TESTS
    public function testGoodUpdate():void{
        $book = clone self::$sampleBook;
        $book->title = "NEW TITLE";
        
        self::$bookRepository->update($book);
        
        $this->assertEquals("NEW TITLE",self::$bookRepository->selectById("objID")->title);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$bookRepository->delete("objID");
        
        $this->assertNull(self::$bookRepository->selectById("objID"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}