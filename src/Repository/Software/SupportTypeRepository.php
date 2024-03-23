<?php

declare(strict_types=1);

namespace App\Repository\Software;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Software\SupportType;
use PDO;
use PDOException;
use App\Util\ORM;

class SupportTypeRepository extends GenericRepository {

    /**
     * Insert support type
     * @param SupportType $supportType  The support type to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(SupportType $supportType): void {

        $query =
            "INSERT INTO supporttype 
            (name) VALUES 
            (:name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $supportType->name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the support type with name: {" . $supportType->name . "}");
        }
    }


    /**
     * Select by id
     * @param int $id    The id to select
     * @return ?SupportType     The support type selected, null if not found
     */
    public function selectById(int $id): ?SupportType {
        $query = "SELECT * FROM supporttype WHERE id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $supportType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($supportType) {
            return ORM::getNewInstance(SupportType::class, $supportType);
        }
        return null;
    }

    /**
     * Select by name
     * @param string $name  The name to select
     * @return ?SupportType The support type selected, null if not found
     */
    public function selectByname(string $name): ?SupportType {
        $query = "SELECT * FROM supporttype WHERE name = :name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $supportType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($supportType) {
            return ORM::getNewInstance(SupportType::class, $supportType);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key  The key to search
     * @return array The support types selected
     */
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM supporttype WHERE name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $supports = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $supports;
    }

    /**
     * Select all
     * @return ?array   The support types selected, null if no results;
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM supporttype";

        $stmt = $this->pdo->query($query);

        $supports = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $supports;
    }

    /**
     * Update support type
     * @param SupportType $s    The support type to update
     * @throws RepositoryException  If the update fails
     */
    public function update(SupportType $s): void {
        $query =
            "UPDATE supporttype 
            SET name = :name            
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $s->name, PDO::PARAM_STR);
        $stmt->bindParam("id", $s->id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the support type with id: {" . $s->id . "}");
        }
    }

    /**
     * Delete a support type
     * @param int $id    The id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $id): void {
        $query =
            "DELETE FROM supporttype          
            WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the support type with id: {" . $id . "}");
        }
    }
}
