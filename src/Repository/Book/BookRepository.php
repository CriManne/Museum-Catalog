<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Book\Book;
use App\Model\Book\BookAuthor;
use App\Repository\Book\AuthorRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\Book\BookAuthorRepository;
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
            "INSERT INTO book
                (ObjectID,Title,PublisherID,Year,Pages,ISBN) VALUES 
                (:ObjectID,:Title,:PublisherID,:Year,:Pages,:ISBN);";

        $queryObject =
            "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag)
                VALUES
                (:ObjectID,:Note,:Url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':ObjectID', $book->ObjectID, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $book->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $book->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $book->Tag, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryBook);
            $stmt->bindParam("ObjectID", $book->ObjectID, PDO::PARAM_STR);
            $stmt->bindParam("Title", $book->Title, PDO::PARAM_STR);
            $stmt->bindParam("PublisherID", $book->Publisher->PublisherID, PDO::PARAM_INT);
            $stmt->bindParam("Year", $book->Year, PDO::PARAM_INT);
            $stmt->bindParam("Pages", $book->Pages, PDO::PARAM_INT);
            $stmt->bindParam("ISBN", $book->ISBN, PDO::PARAM_STR);

            $stmt->execute();
            foreach ($book->Authors as $author) {
                $this->bookAuthorRepository->insert(new BookAuthor($book->ObjectID, $author->AuthorID));
            }

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the book with id: {" . $book->ObjectID . "}");
        }
    }

    /**
     * Select book by id
     * @param string $ObjectID  The object id to select
     * @return ?Book    The book selected, null if not found
     */
    public function selectById(string $ObjectID): ?Book {
        $query = "SELECT * FROM book b 
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE g.ObjectID = :ObjectID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($book) {
            return $this->returnMappedObject($book);
        }
        return null;
    }

    /**
     * Select book by title
     * @param string $Title     The book title to select
     * @return ?Book    The book selected, null if not found
     */
    public function selectByTitle(string $Title): ?Book {
        $query = "SELECT * FROM book b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE Title LIKE :Title";

        $Title = '%'.$Title.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Title", $Title, PDO::PARAM_STR);
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
    public function selectByKey(string $key): array {
        $query = "SELECT DISTINCT g.*,b.* FROM book b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            INNER JOIN publisher p ON b.PublisherID = p.PublisherID
            INNER JOIN bookauthor ba ON b.ObjectID = ba.BookID
            INNER JOIN author a ON ba.AuthorID = a.AuthorID
            WHERE Title LIKE :key OR
            Year LIKE :key OR
            ISBN LIKE :key OR
            a.firstname LIKE :key OR
            a.lastname LIKE :key OR
            g.Note LIKE :key OR
            g.Tag LIKE :key OR
            g.ObjectID LIKE :key";

        $key = '%' . $key . '%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();

        $arr_book = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_book;
    }

    /**
     * Select all books
     * @return ?array   All books, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM book b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID";

        $stmt = $this->pdo->query($query);

        $arr_book = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_book;
    }

    /**
     * Update a book
     * @param Book $s   The book to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Book $b): void {
        $queryBook =
            "UPDATE book
            SET Title = :Title,
            PublisherID = :PublisherID,
            Year = :Year,
            Pages = :Pages,
            ISBN = :ISBN
            WHERE ObjectID = :ObjectID";

        $queryObject =
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag
            WHERE ObjectID = :ObjectID";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryBook);
            $stmt->bindParam("Title", $b->Title, PDO::PARAM_STR);
            $stmt->bindParam("PublisherID", $b->Publisher->PublisherID, PDO::PARAM_INT);
            $stmt->bindParam("Year", $b->Year, PDO::PARAM_INT);
            $stmt->bindParam("Pages", $b->Pages, PDO::PARAM_INT);
            $stmt->bindParam("ISBN", $b->ISBN, PDO::PARAM_STR);
            $stmt->bindParam("ObjectID", $b->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("Note", $b->Note, PDO::PARAM_STR);
            $stmt->bindParam("Url", $b->Url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $b->Tag, PDO::PARAM_STR);
            $stmt->bindParam("ObjectID", $b->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->bookAuthorRepository->deleteById($b->ObjectID);

            foreach ($b->Authors as $author) {
                $this->bookAuthorRepository->insert(new BookAuthor($b->ObjectID, $author->AuthorID));
            }

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the book with id: {" . $b->ObjectID . "}");
        }
    }

    /**
     * Delete a book
     * @param string $ObjectID  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $ObjectID): void {
        try {
            $this->pdo->beginTransaction();

            $query =
                "DELETE FROM bookauthor
            WHERE BookID = :ObjectID;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $query =
                "DELETE FROM book
            WHERE ObjectID = :ObjectID;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $query =
                "DELETE FROM genericobject
            WHERE ObjectID = :ObjectID;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the book with id: {" . $ObjectID . "}");
        }

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while deleting the book with id: {" . $ObjectID . "}");
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
                $bookAuthors[] = ORM::getNewInstance(BookAuthor::class, [$rawBook["ObjectID"], $value]);
            }
        } else {
            $bookAuthors = $this->bookAuthorRepository->selectByBookId($rawBook["ObjectID"]);
        }
        $authors = [];
        if ($bookAuthors) {
            foreach ($bookAuthors as $bookAuthor) {
                $authors[] = $this->authorRepository->selectById(intval($bookAuthor->AuthorID), null);
            }
        }

        return new Book(
            $rawBook["ObjectID"],
            $rawBook["Note"] ?? null,
            $rawBook["Url"] ?? null,
            $rawBook["Tag"] ?? null,
            $rawBook["Title"],
            $this->publisherRepository->selectById(intval($rawBook["PublisherID"]), null),
            intval($rawBook["Year"]),
            $rawBook["ISBN"] ?? null,
            $rawBook["Pages"] ? intval($rawBook["Pages"]) : null,
            $authors
        );
    }
}
