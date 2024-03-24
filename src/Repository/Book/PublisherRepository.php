<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Book\Publisher;
use PDO;
use PDOException;
use App\Util\ORM;

class PublisherRepository extends GenericRepository {

    /**
     * Insert a publisher
     * @param Publisher $publisher  The publisher to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(Publisher $publisher): void {

        $query =
            "INSERT INTO Publisher 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $publisher->name);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the publisher with name: {" . $publisher->name . "}");
        }
    }

    /**
     * Select publisher by id
     * @param int $id  The publisher id
     * @return ?Publisher   The publisher selected, null if not found         * 
     */
    public function findById(int $id): ?Publisher {
        $query = "SELECT * FROM Publisher WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($publisher) {
            return ORM::getNewInstance(Publisher::class, $publisher);
        }
        return null;
    }

    /**
     * Select publisher by name
     * @param string $name  The publisher name
     * @return ?Publisher   The publisher selected,null if not found
     */
    public function selectByName(string $name): ?Publisher {
        $query = "SELECT * FROM Publisher WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($publisher) {
            return ORM::getNewInstance(Publisher::class, $publisher);
        }
        return null;
    }

    /**
     * Select publisher by key
     * @param string $key
     * @return array   The publishers selected
     */
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM Publisher WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all publishers
     * @return ?array   The selected publishers, null if no result
     */
    public function findAll(): ?array {
        $query = "SELECT * FROM Publisher";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a publisher
     * @param Publisher $p  The publisher to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Publisher $p): void {
        $query =
            "UPDATE Publisher 
            SET name = :name
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $p->name);
        $stmt->bindParam("id", $p->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the publisher with id: {" . $p->id . "}");
        }
    }

    /**
     * Delete a publisher
     * @param int $id  The publisher id to delete
     * @throws RepositoryException If the delete fails         * 
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM Publisher 
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the publisher with id: {" . $id . "}");
        }
    }
}
