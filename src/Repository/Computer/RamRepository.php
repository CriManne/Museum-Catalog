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
            "INSERT INTO ram 
            (ModelName,Size) VALUES 
            (:name,:size);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $ram->ModelName, PDO::PARAM_STR);
        $stmt->bindParam("size", $ram->Size, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the ram with name: {" . $ram->ModelName . "}");
        }
    }

    /**
     * Select a ram by id
     * @param int $RamID    The ram id to select
     * @return ?Ram     The selected ram, null if not found
     */
    public function selectById(int $RamID): ?Ram {
        $query = "SELECT * FROM ram WHERE RamID = :RamID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("RamID", $RamID, PDO::PARAM_INT);
        $stmt->execute();
        $ram = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ram) {
            return ORM::getNewInstance(Ram::class, $ram);
        }
        return null;
    }

    /**
     * Select ram by name
     * @param string $ModelName     The ram name to select
     * @return ?Ram     The ram selected,null if not found
     */
    public function selectByName(string $ModelName): ?Ram {
        $query = "SELECT * FROM ram WHERE ModelName = :ModelName";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ModelName", $ModelName, PDO::PARAM_STR);
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
        $query = "SELECT * FROM ram WHERE ModelName LIKE :key OR Size LIKE :key";

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
        $query = "SELECT * FROM ram";

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
            "UPDATE ram 
            SET ModelName = :ModelName,
            Size = :Size
            WHERE RamID = :RamID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ModelName", $ram->ModelName, PDO::PARAM_STR);
        $stmt->bindParam("Size", $ram->Size, PDO::PARAM_STR);
        $stmt->bindParam("RamID", $ram->RamID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the ram with id: {" . $ram->RamID . "}");
        }
    }

    /**
     * Delete a ram
     * @param int $RamID    The ram id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $RamID): void {
        $query =
            "DELETE FROM ram  
            WHERE RamID = :RamID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("RamID", $RamID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the ram with id: {" . $RamID . "}");
        }
    }
}
