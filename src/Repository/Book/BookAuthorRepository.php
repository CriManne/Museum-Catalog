<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Book\BookAuthor;
use PDO;
use PDOException;
use App\Util\ORM;

class BookAuthorRepository extends GenericRepository {

    /**
     * Insert a book author
     * @param BookAuthor $bookAuthor    The book author to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(BookAuthor $bookAuthor): void {

        $query =
            "INSERT INTO bookauthor 
            (BookID,AuthorID) VALUES 
            (:BookID,:AuthorID);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("BookID", $bookAuthor->BookID, PDO::PARAM_STR);
        $stmt->bindParam("AuthorID", $bookAuthor->AuthorID, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the book author {" . $bookAuthor->BookID . "," . $bookAuthor->AuthorID . "}");
        }
    }

    /**
     * Select book author by id
     * @param string $BookID   The book id
     * @param int $AuthorID    The author id
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function selectById(string $BookID, int $AuthorID): ?BookAuthor {
        $query = "SELECT * FROM bookauthor WHERE BookID = :BookID AND AuthorID = :AuthorID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("BookID", $BookID, PDO::PARAM_STR);
        $stmt->bindParam("AuthorID", $AuthorID, PDO::PARAM_INT);
        $stmt->execute();
        $bookAuthor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookAuthor) {
            return ORM::getNewInstance(BookAuthor::class, $bookAuthor);
        }

        return null;
    }

    /**
     * Select book authors by book id
     * @param string $BookID   The book id
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function selectByBookId(string $BookID): ?array {
        $query = "SELECT * FROM bookauthor WHERE BookID = :BookID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("BookID", $BookID, PDO::PARAM_STR);
        $stmt->execute();
        $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASS);

        if ($bookAuthors) {
            return $bookAuthors;
        }

        return null;
    }

    /**
     * Select book authors by author id
     * @param int $AuthorID    The author id
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function selectByAuthorId(int $AuthorID): ?array {
        $query = "SELECT * FROM bookauthor WHERE AuthorID = :AuthorID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("AuthorID", $AuthorID, PDO::PARAM_INT);
        $stmt->execute();
        $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASS);

        if ($bookAuthors) {
            return $bookAuthors;
        }

        return null;
    }

    /**
     * Select all book authors
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM bookauthor";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASS);

        if ($bookAuthors) {
            return $bookAuthors;
        }

        return null;
    }

    /**
     * Delete by id
     * @param string $BookID The book id
     * @throws PDOException If the delete fails
     */
    public function deleteById(string $BookID): void {
        $query =
            "DELETE FROM bookauthor                      
            WHERE BookID = :BookID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("BookID", $BookID, PDO::PARAM_STR);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the book author with id: {" . $BookID . "}");
        }
    }
}
