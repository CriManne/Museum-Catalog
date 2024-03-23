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
     * @param SoftwareType  $softwareType   The software type to insert
     * @throws RepositoryException If the insert fails
     */
    public function insert(SoftwareType $softwareType): void {

        $query =
            "INSERT INTO softwaretype 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $softwareType->name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the software type with name: {" . $softwareType->name . "}");
        }
    }

    /**
     * Select by id
     * @param int $id   The id to select
     * @return ?SoftwareType    The software type selected, null if not found
     */
    public function selectById(int $id): ?SoftwareType {
        $query = "SELECT * FROM softwaretype WHERE id = :id";

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
    public function selectByname(string $name): ?SoftwareType {
        $query = "SELECT * FROM softwaretype WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name, PDO::PARAM_STR);
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
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM softwaretype WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_software = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_software;
    }

    /**
     * Select all 
     * @return ?array   The software types selected, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM softwaretype";

        $stmt = $this->pdo->query($query);

        $arr_software = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_software;
    }

    /**
     * Update a software type
     * @param SoftwareType  The software type to update
     * @throws RepositoryException  If the update fails
     */
    public function update(SoftwareType $s): void {
        $query =
            "UPDATE softwaretype 
            SET name = :name            
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $s->name, PDO::PARAM_STR);
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
            "DELETE FROM softwaretype       
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
