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
            (Name) VALUES 
            (:Name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $supportType->Name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the support type with name: {" . $supportType->Name . "}");
        }
    }


    /**
     * Select by id
     * @param int $SupportTypeID    The id to select
     * @return ?SupportType     The support type selected, null if not found
     */
    public function selectById(int $SupportTypeID): ?SupportType {
        $query = "SELECT * FROM supporttype WHERE SupportTypeID = :SupportTypeID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("SupportTypeID", $SupportTypeID, PDO::PARAM_INT);
        $stmt->execute();
        $supportType = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($supportType) {
            return ORM::getNewInstance(SupportType::class, $supportType);
        }
        return null;
    }

    /**
     * Select by name
     * @param string $Name  The name to select
     * @return ?SupportType The support type selected, null if not found
     */
    public function selectByName(string $Name): ?SupportType {
        $query = "SELECT * FROM supporttype WHERE Name = :Name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $Name, PDO::PARAM_STR);
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
        $query = "SELECT * FROM supporttype WHERE Name LIKE :key";

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
            SET Name = :Name            
            WHERE SupportTypeID = :SupportTypeID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $s->Name, PDO::PARAM_STR);
        $stmt->bindParam("SupportTypeID", $s->SupportTypeID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the support type with id: {" . $s->SupportTypeID . "}");
        }
    }

    /**
     * Delete a support type
     * @param int $SupportTypeID    The id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $SupportTypeID): void {
        $query =
            "DELETE FROM supporttype          
            WHERE SupportTypeID = :SupportTypeID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("SupportTypeID", $SupportTypeID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the support type with id: {" . $SupportTypeID . "}");
        }
    }
}
