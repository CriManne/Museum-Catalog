<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Book\Book;
use App\Model\Book\BookAuthor;
use App\Util\ORM;
use PDO;
use PDOException;

class BookRepository extends GenericRepository {

    public PublisherRepository $publisherRepository;
    public AuthorRepository $authorRepository;
    public BookAuthorRepository $bookAuthorRepository;

    public function __construct(
        PDO $pdo,
        PublisherRepository $publisherRepository,
        AuthorRepository $authorRepository,
        BookAuthorRepository $bookAuthorRepository
    ) {
        parent::__construct($pdo);
        $this->publisherRepository = $publisherRepository;
        $this->authorRepository = $authorRepository;
        $this->bookAuthorRepository = $bookAuthorRepository;
    }

    /**
     * Insert Book
     * @param Book $book    The book to insert
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Book $book): void {

        $queryBook =
            "INSERT INTO Book
                (objectId,title,publisherId,year,pages,isbn) VALUES 
                (:objectId,:title,:publisherId,:year,:pages,:isbn);";

        $queryObject =
            "INSERT INTO GenericObject
                (id,note,url,tag)
                VALUES
                (:objectId,:note,:url,:tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $book->objectId);
            $stmt->bindValue(':note', $book->note);
            $stmt->bindValue(':url', $book->url);
            $stmt->bindValue(':tag', $book->tag);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryBook);
            $stmt->bindParam("objectId", $book->objectId);
            $stmt->bindParam("title", $book->title);
            $stmt->bindParam("publisherId", $book->publisher->id, PDO::PARAM_INT);
            $stmt->bindParam("year", $book->year, PDO::PARAM_INT);
            $stmt->bindParam("pages", $book->pages, PDO::PARAM_INT);
            $stmt->bindParam("isbn", $book->isbn);

            $stmt->execute();
            foreach ($book->authors as $author) {
                $this->bookAuthorRepository->insert(new BookAuthor($book->objectId, $author->id));
            }

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the book with id: {" . $book->objectId . "}");
        }
    }

    /**
     * Select book by id
     * @param string $objectId  The object id to select
     * @return ?Book    The book selected, null if not found
     */
    public function findById(string $objectId): ?Book {
        $query = "SELECT * FROM Book b 
            INNER JOIN GenericObject g ON g.id = b.objectId 
            WHERE g.id = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($book) {
            return $this->returnMappedObject($book);
        }
        return null;
    }

    /**
     * Select book by title
     * @param string $title     The book title to select
     * @return ?Book    The book selected, null if not found
     */
    public function findByTitle(string $title): ?Book {
        $query = "SELECT * FROM Book b
            INNER JOIN GenericObject g ON g.id = b.objectId 
            WHERE title LIKE :title";

        $title = '%' . $title . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("title", $title);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($book) {
            return $this->returnMappedObject($book);
        }
        return null;
    }

    /**
     * Select book by key
     * @param string $key     The key given
     * @return array    The books selected, empty array if no result
     */
    public function findByKey(string $key): array {
        $query = "SELECT DISTINCT g.*,b.* FROM Book b
            INNER JOIN GenericObject g ON g.id = b.objectId 
            INNER JOIN Publisher p ON b.publisherId = p.id
            INNER JOIN BookAuthor ba ON b.objectId = ba.bookId
            INNER JOIN Author a ON ba.authorId = a.id
            WHERE title LIKE :key OR
            year LIKE :key OR
            isbn LIKE :key OR
            a.firstname LIKE :key OR
            a.lastname LIKE :key OR
            g.note LIKE :key OR
            g.tag LIKE :key OR
            g.id LIKE :key";

        $key = '%' . $key . '%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all books
     * @return ?array   All books, null if no result
     */
    public function findAll(): ?array {
        $query = "SELECT * FROM Book b
            INNER JOIN GenericObject g ON g.id = b.objectId";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a book
     * @param Book $b
     * @throws RepositoryException If the update fails
     */
    public function update(Book $b): void {
        $queryBook =
            "UPDATE Book
            SET title = :title,
            publisherId = :publisherId,
            year = :year,
            pages = :pages,
            isbn = :isbn
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE GenericObject
            SET note = :note,
            url = :url,
            tag = :tag
            WHERE id = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryBook);
            $stmt->bindParam("title", $b->title);
            $stmt->bindParam("publisherId", $b->publisher->id, PDO::PARAM_INT);
            $stmt->bindParam("year", $b->year, PDO::PARAM_INT);
            $stmt->bindParam("pages", $b->pages, PDO::PARAM_INT);
            $stmt->bindParam("isbn", $b->isbn);
            $stmt->bindParam("objectId", $b->objectId);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $b->note);
            $stmt->bindParam("url", $b->url);
            $stmt->bindParam("tag", $b->tag);
            $stmt->bindParam("objectId", $b->objectId);
            $stmt->execute();

            $this->bookAuthorRepository->deleteById($b->objectId);

            foreach ($b->authors as $author) {
                $this->bookAuthorRepository->insert(new BookAuthor($b->objectId, $author->id));
            }

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the book with id: {" . $b->objectId . "}");
        }
    }

    /**
     * Delete a book
     * @param string $objectId  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void {
        try {
            $this->pdo->beginTransaction();

            $query =
                "DELETE FROM BookAuthor
            WHERE bookId = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
            $stmt->execute();

            $query =
                "DELETE FROM Book
            WHERE objectId = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
            $stmt->execute();

            $query =
                "DELETE FROM GenericObject
            WHERE id = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the book with id: {" . $objectId . "}");
        }

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while deleting the book with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Book from an array
     * @param array $rawBook    The raw book object
     * @return Book The new instance of book with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawBook): Book {
        $bookAuthors = [];
        /**
         * This method can be called both when fetching the book and when inserting the book
         * So when a book is inserted it has no relation with bookauthor so we can't fetch it
         * from the bookauthor repository but we need to create BookAuthor entity inside the
         * book entity so in the insert method of BookRepository it will add the columns in bookauthor
         */
        if (isset($rawBook["newAuthors"])) {
            foreach ($rawBook["newAuthors"] as $key => $value) {
                $bookAuthors[] = ORM::getNewInstance(BookAuthor::class, [$rawBook["objectId"], $value]);
            }
        } else {
            $bookAuthors = $this->bookAuthorRepository->selectByBookId($rawBook["objectId"]);
        }
        $authors = [];
        if ($bookAuthors) {
            foreach ($bookAuthors as $bookAuthor) {
                $authors[] = $this->authorRepository->findById(intval($bookAuthor->authorId));
            }
        }

        return new Book(
            $rawBook["objectId"],
            $rawBook["title"],
            $this->publisherRepository->findById(intval($rawBook["publisherId"])),
            intval($rawBook["year"]),
            $authors,
            $rawBook["note"] ?? null,
            $rawBook["url"] ?? null,
            $rawBook["tag"] ?? null,
            $rawBook["isbn"] ?? null,
            isset($rawBook["pages"]) ? intval($rawBook["pages"]) : null,
        );
    }
}
