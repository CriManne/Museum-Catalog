<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Peripheral\Peripheral;
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
            (Name) VALUES 
            (:Name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $peripheralType->Name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the peripheraltype with name: {" . $peripheralType->Name . "}");
        }
    }

    /**
     * Select p.type by id
     * @param int $PeripheralTypeID The p.type id to select
     * @param ?bool $showErased
     * @return ?PeripheralType  The p.type selected, null if not found
     */
    public function selectById(int $PeripheralTypeID, ?bool $showErased = false): ?PeripheralType {
        $query = "SELECT * FROM peripheraltype WHERE PeripheralTypeID = :PeripheralTypeID";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("PeripheralTypeID", $PeripheralTypeID, PDO::PARAM_INT);
        $stmt->execute();
        $peripheralType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheralType) {
            return ORM::getNewInstance(PeripheralType::class, $peripheralType);
        }
        return null;
    }

    /**
     * Select p.type by name
     * @param string $Name The p.type id to select
     * @param ?bool $showErased
     * @return ?PeripheralType  The p.type selected, null if not found
     */
    public function selectByName(string $Name, ?bool $showErased = false): ?PeripheralType {
        $query = "SELECT * FROM peripheraltype WHERE Name = :Name";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $Name, PDO::PARAM_STR);
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
     * @param ?bool $showErased
     * @return array  The p.types selected
     */
    public function selectByKey(string $key, ?bool $showErased = false): array {
        $query = "SELECT * FROM peripheraltype WHERE Name LIKE :key";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $key = '%'.$key.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_pt = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_pt;
    }

    /**
     * Select all p.type
     * @param ?bool $showErased
     * @return ?array   All the p.types, null if no result
     */
    public function selectAll(?bool $showErased = false): ?array {
        $query = "SELECT * FROM peripheraltype";

        if (isset($showErased)) {
            $query .= " WHERE Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

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
            SET Name = :Name            
            WHERE PeripheralTypeID = :PeripheralTypeID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $pt->Name, PDO::PARAM_STR);
        $stmt->bindParam("PeripheralTypeID", $pt->PeripheralTypeID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the peripheraltype  with id: {" . $pt->PeripheralTypeID . "}");
        }
    }

    /**
     * Delete a p.type
     * @param int $PeripheralTypeID The p.type id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $PeripheralTypeID): void {
        $query =
            "UPDATE peripheraltype          
            SET Erased = NOW()
            WHERE PeripheralTypeID = :PeripheralTypeID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("PeripheralTypeID", $PeripheralTypeID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the peripheraltype  with id: {" . $PeripheralTypeID . "}");
        }
    }
}
