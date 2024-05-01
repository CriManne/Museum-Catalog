<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use App\Repository\BaseRepository;
use App\Exception\RepositoryException;
use App\Model\Peripheral\PeripheralType;
use PDO;
use PDOException;
use App\Util\ORM;

class PeripheralTypeRepository extends BaseRepository
{
    /**
     * Insert a peripheral type
     * @param PeripheralType $peripheralType The p.type to save
     * @throws RepositoryException  If the save fails
     */
    public function save(PeripheralType $peripheralType): void
    {

        $query =
            "INSERT INTO PeripheralType 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $peripheralType->name);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while saveing the peripheraltype with name: {" . $peripheralType->name . "}");
        }
    }

    /**
     * Select p.type by id
     * @param int $id The p.type id to select
     * @return ?PeripheralType  The p.type selected, null if not found
     */
    public function findById(int $id): ?PeripheralType
    {
        $query = "SELECT * FROM PeripheralType WHERE id = :id";

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
    public function findByName(string $name): ?PeripheralType
    {
        $query = "SELECT * FROM PeripheralType WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name);
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
    public function findByQuery(string $key): array
    {
        $query = "SELECT * FROM PeripheralType WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all p.type
     * @return ?array   All the p.types, null if no result
     */
    public function find(): ?array
    {
        $query = "SELECT * FROM PeripheralType";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update p.type
     * @param PeripheralType $pt The p.type to update
     * @throws RepositoryException  If the update fails
     */
    public function update(PeripheralType $pt): void
    {
        $query =
            "UPDATE PeripheralType 
            SET name = :name            
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $pt->name);
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
    public function delete(int $id): void
    {
        $query =
            "DELETE FROM PeripheralType          
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
