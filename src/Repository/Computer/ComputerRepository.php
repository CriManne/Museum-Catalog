<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;

use App\Model\Computer\Computer;

use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\RamRepository;
use App\Repository\Computer\OsRepository;

use PDO;
use PDOException;

class ComputerRepository extends GenericRepository {

    public CpuRepository $cpuRepository;
    public RamRepository $ramRepository;
    public OsRepository $osRepository;

    public function __construct(
        PDO $pdo,
        CpuRepository $cpuRepository,
        RamRepository $ramRepository,
        OsRepository $osRepository
    ) {
        parent::__construct($pdo);
        $this->cpuRepository = $cpuRepository;
        $this->ramRepository = $ramRepository;
        $this->osRepository = $osRepository;
    }

    /**
     * Insert Computer
     * @param Computer $computer    The computer to insert
     * @throws RepositoryException  If the insert fails     
     */
    public function insert(Computer $computer): void {

        $queryComputer =
            "INSERT INTO computer
                (objectId,ModelName,Year,HddSize,CpuID,RamID,OsID) VALUES 
                (:objectId,:ModelName,:Year,:HddSize,:CpuID,:RamID,:OsID);";

        $queryObject =
            "INSERT INTO genericobject
                (objectId,Note,Url,Tag)
                VALUES
                (:objectId,:Note,:Url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $computer->objectId, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $computer->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $computer->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $computer->Tag, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryComputer);
            $stmt->bindParam("objectId", $computer->objectId, PDO::PARAM_STR);
            $stmt->bindParam("ModelName", $computer->ModelName, PDO::PARAM_STR);
            $stmt->bindParam("Year", $computer->Year, PDO::PARAM_INT);
            $stmt->bindParam("HddSize", $computer->HddSize, PDO::PARAM_STR);
            $stmt->bindParam("CpuID", $computer->Cpu->CpuID, PDO::PARAM_INT);
            $stmt->bindParam("RamID", $computer->Ram->RamID, PDO::PARAM_INT);

            $OsID = !is_null($computer->Os) ? $computer->Os->OsID : null;

            $stmt->bindParam("OsID", $OsID, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the computer with id: {" . $computer->objectId . "}");
        }
    }

    /**
     * Select computer by id
     * @param string $objectId  The object id to select
     * @return ?Computer    The computer selected, null if not found
     */
    public function selectById(string $objectId): ?Computer {
        $query = "SELECT * FROM computer b 
            INNER JOIN genericobject g ON g.objectId = b.objectId 
            WHERE g.objectId = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
        $stmt->execute();
        $computer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($computer) {
            return $this->returnMappedObject($computer);
        }
        return null;
    }

    /**
     * Select computer by model name
     * @param string $ModelName     The computer model name to select
     * @return ?Computer    The computer selected, null if not found
     */
    public function selectByModelName(string $ModelName): ?Computer {
        $query = "SELECT * FROM computer b
            INNER JOIN genericobject g ON g.objectId = b.objectId 
            WHERE ModelName LIKE :ModelName";

        $ModelName = '%' . $ModelName . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ModelName", $ModelName, PDO::PARAM_STR);
        $stmt->execute();
        $computer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($computer) {
            return $this->returnMappedObject($computer);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array   All computers, empty if no result
     */
    public function selectByKey(string $key): array {
        $query = "SELECT DISTINCT g.*,c.* FROM computer c
            INNER JOIN genericobject g ON g.objectId = c.objectId
            INNER JOIN cpu cp ON c.CpuID = cp.CpuID
            INNER JOIN ram r ON r.RamID = c.RamID
            INNER JOIN os o ON c.OsID = o.OsID
            WHERE c.ModelName LIKE :key OR
            Year LIKE :key OR
            HddSize LIKE :key OR
            cp.ModelName LIKE :key OR
            cp.Speed LIKE :key OR
            r.ModelName LIKE :key OR
            r.Size LIKE :key OR
            o.Name LIKE :key OR
            g.Note LIKE :key OR
            g.Tag LIKE :key OR
            g.objectId LIKE :key";

        $key = '%' . $key . '%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();

        $arr_computer = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_computer;
    }

    /**
     * Select all computers
     * @return ?array   All computers, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM computer b
            INNER JOIN genericobject g ON g.objectId = b.objectId";

        $stmt = $this->pdo->query($query);

        $arr_computer = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_computer;
    }

    /**
     * Update a computer
     * @param Computer $s   The computer to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Computer $b): void {
        $queryComputer =
            "UPDATE computer
            SET ModelName = :ModelName,
            Year = :Year,
            HddSize = :HddSize,
            CpuID = :CpuID,
            RamID = :RamID,
            OsID = :OsID            
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag
            WHERE objectId = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryComputer);
            $stmt->bindParam("ModelName", $b->ModelName, PDO::PARAM_STR);
            $stmt->bindParam("Year", $b->Year, PDO::PARAM_INT);
            $stmt->bindParam("HddSize", $b->HddSize, PDO::PARAM_STR);
            $stmt->bindParam("CpuID", $b->Cpu->CpuID, PDO::PARAM_INT);
            $stmt->bindParam("RamID", $b->Ram->RamID, PDO::PARAM_INT);
            $OsID = !is_null($b->Os) ? $b->Os->OsID : null;

            $stmt->bindParam("OsID", $OsID, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $b->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("Note", $b->Note, PDO::PARAM_STR);
            $stmt->bindParam("Url", $b->Url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $b->Tag, PDO::PARAM_STR);
            $stmt->bindParam("objectId", $b->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the computer with id: {" . $b->objectId . "}");
        }
    }

    /**
     * Delete a computer
     * @param string $objectId  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM computer 
            WHERE objectId = :objectId";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
            $stmt->execute();

            $query = "DELETE FROM genericobject 
            WHERE objectId = :objectId";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the computer with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Computer from an array
     * @param array $rawComputer    The raw computer object
     * @return Computer The new instance of computer with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawComputer): Computer {

        $os = isset($rawComputer["OsID"]) ? $this->osRepository->selectById(intval($rawComputer["OsID"])) : null;

        return new Computer(
            $rawComputer["objectId"],
            $rawComputer["Note"] ?? null,
            $rawComputer["Url"] ?? null,
            $rawComputer["Tag"] ?? null,
            $rawComputer["ModelName"],
            intval($rawComputer["Year"]),
            $rawComputer["HddSize"] ?? null,
            $this->cpuRepository->selectById(intval($rawComputer["CpuID"])),
            $this->ramRepository->selectById(intval($rawComputer["RamID"])),
            $os
        );
    }
}
