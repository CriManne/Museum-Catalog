<?php

declare(strict_types=1);

namespace App\Repository\Software;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Software\SoftwareType;
use PDO;
use PDOException;
use App\Util\ORM;

class SoftwareTypeRepository extends GenericRepository {

    /**
     * Insert softwaretype
     * @param SoftwareType  $softwareType   The software type to save
     * @throws RepositoryException If the save fails
     */
    public function save(SoftwareType $softwareType): void {

        $query =
            "INSERT INTO SoftwareType 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $softwareType->name);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while saveing the software type with name: {" . $softwareType->name . "}");
        }
    }

    /**
     * Select by id
     * @param int $id   The id to select
     * @return ?SoftwareType    The software type selected, null if not found
     */
    public function findById(int $id): ?SoftwareType {
        $query = "SELECT * FROM SoftwareType WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $softwareType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($softwareType) {
            return ORM::getNewInstance(SoftwareType::class, $softwareType);
        }
        return null;
    }

    /**
     * Select by name
     * @param string $name  The name to select
     * @return ?SoftwareType    The software type selected, null if not found
     */
    public function findByName(string $name): ?SoftwareType {
        $query = "SELECT * FROM SoftwareType WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $softwareType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($softwareType) {
            return ORM::getNewInstance(SoftwareType::class, $softwareType);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key  The key to search
     * @return array The software types selected
     */
    public function findByQuery(string $key): array {
        $query = "SELECT * FROM SoftwareType WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all 
     * @return ?array   The software types selected, null if no result
     */
    public function find(): ?array {
        $query = "SELECT * FROM SoftwareType";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a software type
     * @param SoftwareType $s
     * @throws RepositoryException If the update fails
     */
    public function update(SoftwareType $s): void {
        $query =
            "UPDATE SoftwareType 
            SET name = :name            
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $s->name);
        $stmt->bindParam("id", $s->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the software type with id: {" . $s->id . "}");
        }
    }

    /**
     * Delete software type
     * @param int $id   The id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM SoftwareType       
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the software type with id: {" . $id . "}");
        }
    }
}
