<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use AbstractRepo\Exceptions\RepositoryException;
use App\Model\Book\Author;
use App\Model\Book\Book;
use App\Model\Book\Publisher;
use App\Model\GenericObject;
use App\Repository\Book\AuthorRepository;
use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\BaseRepositoryTest;
use DI\DependencyException;
use DI\NotFoundException;
use App\Repository\Book\BookHasAuthorRepository;
use App\Model\Book\BookHasAuthor;

final class BookHasAuthorRepositoryTest extends BaseRepositoryTest
{
    public static GenericObjectRepository $genericObjectRepository;
    public static PublisherRepository     $publisherRepository;
    public static BookRepository          $bookRepository;
    public static AuthorRepository        $authorRepository;
    public static BookHasAuthorRepository $bookHasAuthorRepository;

    public static GenericObject $sampleGenericObject;
    public static Publisher     $samplePublisher;
    public static Book          $sampleBook;
    public static Author        $sampleAuthor;
    public static BookHasAuthor $sampleBookHasAuthor;

    /**
     * @return void
     * @throws RepositoryException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$genericObjectRepository = new GenericObjectRepository(self::$pdo);
        self::$bookHasAuthorRepository = new BookHasAuthorRepository(self::$pdo);
        self::$authorRepository        = new AuthorRepository(self::$pdo);
        self::$publisherRepository     = new PublisherRepository(self::$pdo);
        self::$bookRepository          = new BookRepository(self::$pdo, self::$publisherRepository, self::$authorRepository, self::$bookHasAuthorRepository);

        self::$sampleGenericObject = new GenericObject("OBJ1");
        self::$samplePublisher     = new Publisher("PUB");
        self::$sampleBook          = new Book(
            self::$sampleGenericObject,
            "BOOK2",
            self::$samplePublisher,
            2000,
            []
        );
        self::$sampleAuthor        = new Author("Firstname", "Lastname");
        self::$sampleBookHasAuthor = new BookHasAuthor(self::$sampleBook, self::$sampleAuthor);
    }

    public function setUp(): void
    {
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
        self::$pdo->exec("TRUNCATE TABLE GenericObject;");
        self::$pdo->exec("TRUNCATE TABLE BookHasAuthor;");
        self::$pdo->exec("TRUNCATE TABLE Book;");
        self::$pdo->exec("TRUNCATE TABLE Author;");
        self::$pdo->exec("TRUNCATE TABLE Publisher;");
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * @return void
     * @throws RepositoryException
     * @throws \App\Exception\RepositoryException
     */
    public function testGoodInsert(): void
    {
        self::$genericObjectRepository->save(self::$sampleGenericObject);
        self::$publisherRepository->save(self::$samplePublisher);
        self::$authorRepository->save(self::$sampleAuthor);
        self::$bookRepository->save(self::$sampleBook);
        self::$bookHasAuthorRepository->save(self::$sampleBookHasAuthor);

        $this->assertEquals("BOOK2", self::$bookHasAuthorRepository->findById(1)->book->title);
    }

    /**
     * @return void
     * @throws RepositoryException
     */
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$bookHasAuthorRepository->findById(99));
    }

    /**
     * @return void
     * @throws RepositoryException
     */
    public function testGoodDeleteByBookAuthorID(): void
    {

        self::$bookHasAuthorRepository->delete(1);

        $this->assertNull(self::$bookHasAuthorRepository->findById(1));
    }
}