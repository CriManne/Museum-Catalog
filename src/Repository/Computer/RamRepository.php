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
     * @param Ram $ram  The ram to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(Ram $ram): void {

        $query =
            "INSERT INTO Ram 
            (modelName,size) VALUES 
            (:name,:size);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $ram->modelName, PDO::PARAM_STR);
        $stmt->bindParam("size", $ram->size, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the ram with name: {" . $ram->modelName . "}");
        }
    }

    /**
     * Select a ram by id
     * @param int $id    The ram id to select
     * @return ?Ram     The selected ram, null if not found
     */
    public function selectById(int $id): ?Ram {
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
        $stmt->bindParam("modelName", $modelName, PDO::PARAM_STR);
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
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM Ram WHERE modelName LIKE :key OR size LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_ram = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_ram;
    }

    /**
     * Select all rams
     * @return ?array   The rams selected, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM Ram";

        $stmt = $this->pdo->query($query);

        $arr_ram = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_ram;
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
        $stmt->bindParam("modelName", $ram->modelName, PDO::PARAM_STR);
        $stmt->bindParam("size", $ram->size, PDO::PARAM_STR);
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
