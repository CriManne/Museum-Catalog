<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\BaseRepository;
use App\Exception\RepositoryException;
use App\Model\Book\BookHasAuthor;
use PDO;
use PDOException;
use App\Util\ORM;

class BookHasAuthorRepository extends BaseRepository
{
    /**
     * Insert a book author
     * @param BookHasAuthor $bookAuthor The book author to save
     * @throws RepositoryException  If the save fails
     */
    public function save(BookHasAuthor $bookAuthor): void
    {

        $query =
            "INSERT INTO BookHasAuthor 
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
     * @param string $bookId The book id
     * @param int $authorId The author id
     * @return ?BookHasAuthor  The selected book author, null if not found
     */
    public function findById(string $bookId, int $authorId): ?BookHasAuthor
    {
        $query = "SELECT * FROM BookHasAuthor WHERE bookId = :bookId AND authorId = :authorId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("bookId", $bookId);
        $stmt->bindParam("authorId", $authorId, PDO::PARAM_INT);
        $stmt->execute();
        $bookAuthor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookAuthor) {
            return ORM::getNewInstance(BookHasAuthor::class, $bookAuthor);
        }

        return null;
    }

    /**
     * Select book authors by book id
     * @param string $bookId The book id
     * @return ?BookHasAuthor  The selected book author, null if not found
     */
    public function findByBookId(string $bookId): ?array
    {
        $query = "SELECT * FROM BookHasAuthor WHERE bookId = :bookId";

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
     * @param int $authorId The author id
     * @return ?BookHasAuthor  The selected book author, null if not found
     */
    public function findByAuthorId(int $authorId): ?array
    {
        $query = "SELECT * FROM BookHasAuthor WHERE authorId = :authorId";

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
     * @return ?BookHasAuthor  The selected book author, null if not found
     */
    public function find(): ?array
    {
        $query = "SELECT * FROM BookHasAuthor";

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
    public function deleteById(string $bookId): void
    {
        $query =
            "DELETE FROM BookHasAuthor                      
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
