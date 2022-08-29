<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Book\Author;
use PDO;
use PDOException;
use App\Util\ORM;

class AuthorRepository extends GenericRepository {

    /**
     * Insert an author
     * @param Author $author    The author to insert
     * @return Author           The author inserted
     * @throws RepositoryException  If the insert fails
     */
    public function insert(Author $author): Author {

        $query =
            "INSERT INTO author 
            (firstname,lastname) VALUES 
            (:firstname,:lastname);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("firstname", $author->firstname, PDO::PARAM_STR);
        $stmt->bindParam("lastname", $author->lastname, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $author->AuthorID = intval($this->pdo->lastInsertId());
            return $author;
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the author with name: {" . $author->firstname . "}");
        }
    }

    /**
     * Select author by id
     * @param int $id   The author id
     * @param ?bool $showErased     If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
     * @return ?Author  The selected author, null if not found
     */
    public function selectById(int $id, ?bool $showErased = false): ?Author {
        $query = "SELECT * FROM author WHERE AuthorID = :id";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($author) {
            return ORM::getNewInstance(Author::class, $author);
        }
        return null;
    }

    /**
     * Select by fullname
     * @param string $fullname  The author fullname
     * @param ?bool $showErased If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
     * @return ?Author  The selected Author, null if not found
     */
    public function selectByFullName(string $fullname, ?bool $showErased = false): ?Author {
        $query = "SELECT * FROM author WHERE Concat(firstname,' ',lastname) = :fullname OR Concat(lastname,' ',firstname) = :fullname";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("fullname", $fullname, PDO::PARAM_STR);
        $stmt->execute();
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($author) {
            return ORM::getNewInstance(Author::class, $author);
        }
        return null;
    }

    /**
     * Select all authors
     * @param ?bool $showErased     If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
     * @return ?array   All the authors, null if author table is empty
     */
    public function selectAll(?bool $showErased = false): ?array {
        $query = "SELECT * FROM author";

        if (isset($showErased)) {
            $query .= " WHERE Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->query($query);

        $arr_cpu = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);

        return $arr_cpu;
    }

    /**
     * Update an author
     * @param Author $a     The author to update
     * @return Author       The author updated
     * @throws RepositoryException  If the update fails
     */
    public function update(Author $author): Author {
        $query =
            "UPDATE author 
            SET firstname = :firstname,
            lastname = :lastname
            WHERE AuthorID = :AuthorID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("firstname", $author->firstname, PDO::PARAM_STR);
        $stmt->bindParam("lastname", $author->lastname, PDO::PARAM_STR);
        $stmt->bindParam("AuthorID", $author->AuthorID, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $author;
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the author with id: {" . $author->AuthorID . "}");
        }
    }

    /**
     * Delete an author
     * @param int $AuthorID     The author id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $AuthorID): void {
        $query =
            "UPDATE author          
            SET Erased = NOW()
            WHERE AuthorID = :AuthorID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("AuthorID", $AuthorID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the author with id: {" . $AuthorID . "}");
        }
    }
}
