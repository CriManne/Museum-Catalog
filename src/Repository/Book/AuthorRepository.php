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
     * @throws RepositoryException  If the insert fails
     */
    public function insert(Author $author): void {

        $query =
            "INSERT INTO author 
            (firstname,lastname) VALUES 
            (:firstname,:lastname);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("firstname", $author->firstname, PDO::PARAM_STR);
        $stmt->bindParam("lastname", $author->lastname, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the author with name: {" . $author->firstname . "}");
        }
    }

    /**
     * Select author by id
     * @param int $id   The author id
     * @return ?Author  The selected author, null if not found
     */
    public function selectById(int $id): ?Author {
        $query = "SELECT * FROM author WHERE id = :id";

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
     * Select by key
     * @param string $key  The key to search
     * @return array  The selected Authors
     */
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM author WHERE 
        Concat(firstname,' ',lastname) LIKE :key OR 
        Concat(lastname,' ',firstname) = :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_aut = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_aut;
    }

    /**
     * Select all authors
     * @return ?array   All the authors, null if author table is empty
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM author";

        $stmt = $this->pdo->query($query);

        $arr_cpu = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_cpu;
    }

    /**
     * Update an author
     * @param Author $a     The author to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Author $author): void {
        $query =
            "UPDATE author 
            SET firstname = :firstname,
            lastname = :lastname
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("firstname", $author->firstname, PDO::PARAM_STR);
        $stmt->bindParam("lastname", $author->lastname, PDO::PARAM_STR);
        $stmt->bindParam("id", $author->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the author with id: {" . $author->id . "}");
        }
    }

    /**
     * Delete an author
     * @param int $id     The author id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM author                      
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the author with id: {" . $id . "}");
        }
    }
}
