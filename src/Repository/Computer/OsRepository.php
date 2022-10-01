<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Computer\Os;
use PDO;
use PDOException;
use App\Util\ORM;

class OsRepository extends GenericRepository {

    /**
     * Insert a os
     * @param Os $os    The os to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(Os $os): void {

        $query =
            "INSERT INTO os 
            (Name) VALUES 
            (:Name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $os->Name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the os with name: {" . $os->Name . "}");
        }
    }

    /**
     * Select os by id
     * @param int $OsID     The os id to select
     * @return ?Os  The os selected, null if not found
     */
    public function selectById(int $OsID): ?Os {
        $query = "SELECT * FROM os WHERE OsID = :OsID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("OsID", $OsID, PDO::PARAM_INT);
        $stmt->execute();
        $os = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($os) {
            return ORM::getNewInstance(Os::class, $os);
        }
        return null;
    }

    /**
     * Select os by name
     * @param string $Name  The os name to select 
     * @return ?Os  The selected os, null if not found
     */
    public function selectByName(string $Name): ?Os {
        $query = "SELECT * FROM os WHERE Name = :Name";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $Name, PDO::PARAM_STR);
        $stmt->execute();
        $os = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($os) {
            return ORM::getNewInstance(Os::class, $os);
        }
        return null;
    }

    /**
     * Select os by key
     * @param string $key  The key to search 
     * @return array  The selected oss
     */
    public function selectByKey(string $key): array {
        $query = "SELECT * FROM os WHERE Name LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_os = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_os;
    }

    /**
     * Select all os
     * @return ?array   The list of os, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM os";

        $stmt = $this->pdo->query($query);

        $arr_os = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_os;
    }

    /**
     * Update a os
     * @param Os $os    The os to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Os $os): void {
        $query =
            "UPDATE os 
            SET Name = :Name            
            WHERE OsID = :OsID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $os->Name, PDO::PARAM_STR);
        $stmt->bindParam("OsID", $os->OsID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the os  with id: {" . $os->OsID . "}");
        }
    }

    /**
     * Delete an os
     * @param int $OsID     The os id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(int $OsID): void {
        $query =
            "DELETE FROM os   
            WHERE OsID = :OsID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("OsID", $OsID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the os  with id: {" . $OsID . "}");
        }
    }
}
