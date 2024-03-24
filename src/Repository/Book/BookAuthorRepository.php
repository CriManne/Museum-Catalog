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
     * @param BookAuthor $bookAuthor    The book author to save
     * @throws RepositoryException  If the save fails
     */
    public function save(BookAuthor $bookAuthor): void {

        $query =
            "INSERT INTO BookAuthor 
            (bookId,authorId) VALUES 
            (:bookId,:authorId);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("bookId", $bookAuthor->bookId);
        $stmt->bindParam("authorId", $bookAuthor->authorId, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while saveing the book author {" . $bookAuthor->bookId . "," . $bookAuthor->authorId . "}");
        }
    }

    /**
     * Select book author by id
     * @param string $bookId   The book id
     * @param int $authorId    The author id
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function findById(string $bookId, int $authorId): ?BookAuthor {
        $query = "SELECT * FROM BookAuthor WHERE bookId = :bookId AND authorId = :authorId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("bookId", $bookId);
        $stmt->bindParam("authorId", $authorId, PDO::PARAM_INT);
        $stmt->execute();
        $bookAuthor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookAuthor) {
            return ORM::getNewInstance(BookAuthor::class, $bookAuthor);
        }

        return null;
    }

    /**
     * Select book authors by book id
     * @param string $bookId   The book id
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function selectByBookId(string $bookId): ?array {
        $query = "SELECT * FROM BookAuthor WHERE bookId = :bookId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("bookId", $bookId);
        $stmt->execute();
        $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASS);

        if ($bookAuthors) {
            return $bookAuthors;
        }

        return null;
    }

    /**
     * Select book authors by author id
     * @param int $authorId    The author id
     * @return ?BookAuthor  The selected book author, null if not found
     */
    public function selectByAuthorId(int $authorId): ?array {
        $query = "SELECT * FROM BookAuthor WHERE authorId = :authorId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("authorId", $authorId, PDO::PARAM_INT);
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
    public function findAll(): ?array {
        $query = "SELECT * FROM BookAuthor";

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
     * @param string $bookId The book id
     * @throws RepositoryException
     */
    public function deleteById(string $bookId): void {
        $query =
            "DELETE FROM BookAuthor                      
            WHERE bookId = :bookId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("bookId", $bookId);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the book author with id: {" . $bookId . "}");
        }
    }
}
