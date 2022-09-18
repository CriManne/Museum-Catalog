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
     * @return Book         The book inserted
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Book $book): Book {

        $queryBook =
            "INSERT INTO book
                (ObjectID,Title,PublisherID,Year,Pages,ISBN) VALUES 
                (:ObjectID,:Title,:PublisherID,:Year,:Pages,:ISBN);";

        $queryObject =
            "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag,Active,Erased)
                VALUES
                (:ObjectID,:Note,:Url,:Tag,:Active,:Erased)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':ObjectID', $book->ObjectID, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $book->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $book->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $book->Tag, PDO::PARAM_STR);
            $stmt->bindValue(':Active', $book->Active, PDO::PARAM_STR);
            $stmt->bindValue(':Erased', $book->Erased, PDO::PARAM_STR);

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
            return $book;
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the book with id: {" . $book->ObjectID . "}");
        }
    }

    /**
     * Select book by id
     * @param string $ObjectID  The object id to select
     * @param ?bool $showErased
     * @return ?Book    The book selected, null if not found
     */
    public function selectById(string $ObjectID, ?bool $showErased = false): ?Book {
        $query = "SELECT * FROM book b 
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE g.ObjectID = :ObjectID";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

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
     * @param ?bool $showErased
     * @return ?Book    The book selected, null if not found
     */
    public function selectByTitle(string $Title, ?bool $showErased = false): ?Book {
        $query = "SELECT * FROM book b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE Title LIKE Concat('%',:Title,'%')";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

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
     * @param ?bool $showErased
     * @return array    The books selected, empty array if no result
     */
    public function selectByKey(string $key, ?bool $showErased = false): array {
        $query = "SELECT * FROM book b
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
            g.Tag LIKE :key";

        if (isset($showErased)) {
            $query .= " AND g.Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $key = '%'.$key.'%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        
        $arr_book = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_book;
    }

    /**
     * Select all books
     * @param ?bool $showErased
     * @return ?array   All books, null if no result
     */
    public function selectAll(?bool $showErased = false): ?array {
        $query = "SELECT * FROM book b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID";

        if (isset($showErased)) {
            $query .= " WHERE Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->query($query);

        $arr_book = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_book;
    }

    /**
     * Update a book
     * @param Book $s   The book to update
     * @return Book     The book updated
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
            Tag = :Tag,
            Active = :Active,
            Erased = :Erased
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
            $stmt->bindParam("Active", $b->Active, PDO::PARAM_STR);
            $stmt->bindParam("Erased", $b->Erased, PDO::PARAM_STR);
            $stmt->bindParam("ObjectID", $b->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

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
        $query =
            "UPDATE genericobject
            SET Erased = NOW()
            WHERE ObjectID = :ObjectID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
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
        $bookAuthors = $this->bookAuthorRepository->selectByBookId($rawBook["ObjectID"]);
        $authors = [];
        foreach ($bookAuthors as $bookAuthor) {
            $authors[] = $this->authorRepository->selectById(intval($bookAuthor->AuthorID));
        }


        return new Book(
            $rawBook["ObjectID"],
            $rawBook["Note"],
            $rawBook["Url"],
            $rawBook["Tag"],
            strval($rawBook["Active"]),
            $rawBook["Erased"],
            $rawBook["Title"],
            $this->publisherRepository->selectById(intval($rawBook["PublisherID"])),
            intval($rawBook["Year"]),
            $rawBook["ISBN"],
            intval($rawBook["Pages"]),
            $authors
        );
    }
}
