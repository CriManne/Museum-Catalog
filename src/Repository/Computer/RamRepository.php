<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Computer\Ram;
use PDO;
use PDOException;
use App\Util\ORM;

class RamRepository extends GenericRepository {

    /**
     * Insert a ram
     * @param Ram $ram  The ram to save
     * @throws RepositoryException  If the save fails
     */
    public function save(Ram $ram): void {

        $query =
            "INSERT INTO Ram 
            (modelName,size) VALUES 
            (:name,:size);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $ram->modelName);
        $stmt->bindParam("size", $ram->size);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while saveing the ram with name: {" . $ram->modelName . "}");
        }
    }

    /**
     * Select a ram by id
     * @param int $id    The ram id to select
     * @return ?Ram     The selected ram, null if not found
     */
    public function findById(int $id): ?Ram {
        $query = "SELECT * FROM Ram WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $ram = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ram) {
            return ORM::getNewInstance(Ram::class, $ram);
        }
        return null;
    }

    /**
     * Select ram by name
     * @param string $modelName     The ram name to select
     * @return ?Ram     The ram selected,null if not found
     */
    public function selectByName(string $modelName): ?Ram {
        $query = "SELECT * FROM Ram WHERE modelName = :modelName";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("modelName", $modelName);
        $stmt->execute();
        $ram = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ram) {
            return ORM::getNewInstance(Ram::class, $ram);
        }
        return null;
    }

    /**
     * Select ram by key
     * @param string $key     The key to search
     * @return array     The rams selected
     */
    public function findByKey(string $key): array {
        $query = "SELECT * FROM Ram WHERE modelName LIKE :key OR size LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all rams
     * @return ?array   The rams selected, null if no result
     */
    public function findAll(): ?array {
        $query = "SELECT * FROM Ram";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a ram
     * @param Ram $ram  The ram to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Ram $ram): void {
        $query =
            "UPDATE Ram 
            SET modelName = :modelName,
            size = :size
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("modelName", $ram->modelName);
        $stmt->bindParam("size", $ram->size);
        $stmt->bindParam("id", $ram->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the ram with id: {" . $ram->id . "}");
        }
    }

    /**
     * Delete a ram
     * @param int $id    The ram id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM Ram  
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the ram with id: {" . $id . "}");
        }
    }
}
