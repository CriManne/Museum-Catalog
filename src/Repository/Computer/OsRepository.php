<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Computer\Os;
use PDO;
use PDOException;
use App\Util\ORM;

class OsRepository extends GenericRepository {

    /**
     * Insert a os
     * @param Os $os    The os to save
     * @throws RepositoryException  If the save fails
     */
    public function save(Os $os): void {

        $query =
            "INSERT INTO Os 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $os->name);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while saveing the os with name: {" . $os->name . "}");
        }
    }

    /**
     * Select os by id
     * @param int $id     The os id to select
     * @return ?Os  The os selected, null if not found
     */
    public function findById(int $id): ?Os {
        $query = "SELECT * FROM Os WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $os = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($os) {
            return ORM::getNewInstance(Os::class, $os);
        }
        return null;
    }

    /**
     * Select os by name
     * @param string $name  The os name to select 
     * @return ?Os  The selected os, null if not found
     */
    public function findByName(string $name): ?Os {
        $query = "SELECT * FROM Os WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $os = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($os) {
            return ORM::getNewInstance(Os::class, $os);
        }
        return null;
    }

    /**
     * Select os by key
     * @param string $key  The key to search 
     * @return array  The selected oss
     */
    public function findByKey(string $key): array {
        $query = "SELECT * FROM Os WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all os
     * @return ?array   The list of os, null if no result
     */
    public function findAll(): ?array {
        $query = "SELECT * FROM Os";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a os
     * @param Os $os    The os to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Os $os): void {
        $query =
            "UPDATE Os 
            SET name = :name            
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $os->name);
        $stmt->bindParam("id", $os->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the os  with id: {" . $os->id . "}");
        }
    }

    /**
     * Delete an os
     * @param int $id     The os id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM Os   
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the os  with id: {" . $id . "}");
        }
    }
}
