<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\BaseRepositoryTest;
use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\Book\AuthorRepository;
use App\Repository\Book\BookHasAuthorRepository;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use App\Models\Book\Author;
use App\Models\Book\Book;
use App\Models\Book\Publisher;

final class BookRepositoryTest extends BaseRepositoryTest
{
    public static GenericObject $sampleGenericObject;
    public static Author        $sampleAuthor;
    public static Book          $sampleBook;
    public static Publisher     $samplePublisher;

    public static GenericObjectRepository $genericObjectRepository;
    public static PublisherRepository     $publisherRepository;
    public static AuthorRepository        $authorRepository;
    public static BookHasAuthorRepository $bookAuthorRepository;
    public static BookRepository          $bookRepository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Repository to handle relations
        self::$genericObjectRepository = new GenericObjectRepository(self::$pdo);
        self::$authorRepository        = new AuthorRepository(self::$pdo);
        self::$bookAuthorRepository    = new BookHasAuthorRepository(self::$pdo);
        self::$publisherRepository     = new PublisherRepository(self::$pdo);
        self::$bookRepository          = new BookRepository(self::$pdo);

        self::$sampleGenericObject = new GenericObject("objID");

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
            self::$sampleGenericObject,
            "1984",
            self::$samplePublisher,
            1984,
            null,
            "AAABBBCCC",
            95,
        );

        self::$authorRepository->save(self::$sampleAuthor);
        self::$publisherRepository->save(self::$samplePublisher);
    }

    public function setUp(): void
    {
        self::$genericObjectRepository->save(self::$sampleGenericObject);
        self::$bookRepository->save(self::$sampleBook);
    }

    public function tearDown(): void
    {
        //Clear the table
        self::$pdo->exec(
            "SET FOREIGN_KEY_CHECKS=0; 
            TRUNCATE TABLE Book; 
            TRUNCATE TABLE GenericObject; 
            TRUNCATE TABLE BookHasAuthor; 
            SET FOREIGN_KEY_CHECKS=1;"
        );
    }

    //INSERT TESTS
    public function testGoodInsert(): void
    {
        $genericObject     = clone self::$sampleGenericObject;
        $genericObject->id = "objID2";

        $book                = clone self::$sampleBook;
        $book->genericObject = $genericObject;
        $book->title         = "2001";

        self::$genericObjectRepository->save($genericObject);
        self::$bookRepository->save($book);

        $this->assertEquals(self::$bookRepository->findById("objID2")->title, "2001");
    }

    public function testBadInsert(): void
    {
        $this->expectException(AbstractRepositoryException::class);
        self::$bookRepository->save(self::$sampleBook);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$bookRepository->findById("objID"));
    }

    public function testBadSelectById(): void
    {
        $this->assertNull(self::$bookRepository->findById("WRONGID"));
    }

    public function testGoodSelectAll(): void
    {
        for ($i = 1; $i < 4; $i++) {
            $genericObject     = clone self::$sampleGenericObject;
            $genericObject->id = "objID" . $i;

            $book                = clone self::$sampleBook;
            $book->genericObject = $genericObject;
            $book->title         = "Test";

            self::$genericObjectRepository->save($genericObject);
            self::$bookRepository->save($book);
        }
        $books = self::$bookRepository->find();

        $this->assertEquals(count($books), 4);
    }


    public function testGoodSelectByKey(): void
    {
        $genericObject     = clone self::$sampleGenericObject;
        $genericObject->id = "objID4";

        $book                = clone self::$sampleBook;
        $book->genericObject = $genericObject;
        $book->title         = "1984 Second edition";

        self::$genericObjectRepository->save($genericObject);
        self::$bookRepository->save($book);

        $this->assertCount(2, self::$bookRepository->findByQuery("84"));
    }

    //UPDATE TESTS
    public function testGoodUpdate(): void
    {
        $book        = clone self::$sampleBook;
        $book->title = "NEW TITLE";

        self::$bookRepository->update($book);

        $this->assertEquals("NEW TITLE", self::$bookRepository->findById("objID")->title);
    }

    //DELETE TESTS
    public function testGoodDelete(): void
    {
        self::$bookRepository->delete("objID");

        $this->assertNull(self::$bookRepository->findById("objID"));
    }
}