<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Peripheral\PeripheralType;
use PDO;
use PDOException;
use App\Util\ORM;

class PeripheralTypeRepository extends GenericRepository {

    /**
     * Insert a peripheral type
     * @param PeripheralType $peripheralType    The p.type to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(PeripheralType $peripheralType): void {

        $query =
            "INSERT INTO peripheraltype 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $peripheralType->name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the peripheraltype with name: {" . $peripheralType->name . "}");
        }
    }

    /**
     * Select p.type by id
     * @param int $id The p.type id to select
     * @return ?PeripheralType  The p.type selected, null if not found
     */
    public function selectById(int $id): ?PeripheralType {
        $query = "SELECT * FROM peripheraltype WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $peripheralType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheralType) {
            return ORM::getNewInstance(PeripheralType::class, $peripheralType);
        }
        return null;
    }

    /**
     * Select p.type by name
     * @param string $name The p.type id to select
     * @return ?PeripheralType  The p.type selected, null if not found
     */
    public function selectByname(string $name): ?PeripheralType {
        $query = "SELECT * FROM peripheraltype WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $peripheralType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheralType) {
            return ORM::getNewInstance(PeripheralType::class, $peripheralType);
        }
        return null;
    }

    /**
     * Select p.type by key
     * @param string $key The key to search
     * @return array  The p.types selected
     */
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM peripheraltype WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_pt = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_pt;
    }

    /**
     * Select all p.type
     * @return ?array   All the p.types, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM peripheraltype";

        $stmt = $this->pdo->query($query);

        $arr_pt = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_pt;
    }

    /**
     * Update p.type
     * @param PeripheralType $pt    The p.type to update
     * @throws RepositoryException  If the update fails
     */
    public function update(PeripheralType $pt): void {
        $query =
            "UPDATE peripheraltype 
            SET name = :name            
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $pt->name, PDO::PARAM_STR);
        $stmt->bindParam("id", $pt->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the peripheraltype  with id: {" . $pt->id . "}");
        }
    }

    /**
     * Delete a p.type
     * @param int $id The p.type id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM peripheraltype          
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the peripheraltype  with id: {" . $id . "}");
        }
    }
}
