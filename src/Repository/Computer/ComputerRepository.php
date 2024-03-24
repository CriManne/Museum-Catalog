<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;

use App\Model\Computer\Computer;

use PDO;
use PDOException;

class ComputerRepository extends GenericRepository {

    public cpuRepository $cpuRepository;
    public ramRepository $ramRepository;
    public osRepository $osRepository;

    public function __construct(
        PDO $pdo,
        cpuRepository $cpuRepository,
        ramRepository $ramRepository,
        osRepository $osRepository
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
            "INSERT INTO Computer
                (objectId,modelName,year,hddSize,cpuId,ramId,osId) VALUES 
                (:objectId,:modelName,:year,:hddSize,:cpuId,:ramId,:osId);";

        $queryObject =
            "INSERT INTO GenericObject
                (id,note,url,tag)
                VALUES
                (:objectId,:note,:url,:tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $computer->objectId);
            $stmt->bindValue(':note', $computer->note);
            $stmt->bindValue(':url', $computer->url);
            $stmt->bindValue(':tag', $computer->tag);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryComputer);
            $stmt->bindParam("objectId", $computer->objectId);
            $stmt->bindParam("modelName", $computer->modelName);
            $stmt->bindParam("year", $computer->year, PDO::PARAM_INT);
            $stmt->bindParam("hddSize", $computer->hddSize);
            $stmt->bindParam("cpuId", $computer->cpu->id, PDO::PARAM_INT);
            $stmt->bindParam("ramId", $computer->ram->id, PDO::PARAM_INT);

            $osId = !is_null($computer->os) ? $computer->os->id : null;

            $stmt->bindParam("osId", $osId, PDO::PARAM_INT);

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
        $query = "SELECT * FROM Computer b 
            INNER JOIN GenericObject g ON g.id = b.objectId 
            WHERE g.id = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId);
        $stmt->execute();
        $computer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($computer) {
            return $this->returnMappedObject($computer);
        }
        return null;
    }

    /**
     * Select computer by model name
     * @param string $modelName     The computer model name to select
     * @return ?Computer    The computer selected, null if not found
     */
    public function selectBymodelName(string $modelName): ?Computer {
        $query = "SELECT * FROM Computer b
            INNER JOIN GenericObject g ON g.id = b.objectId 
            WHERE modelName LIKE :modelName";

        $modelName = '%' . $modelName . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("modelName", $modelName);
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
        $query = "SELECT DISTINCT g.*,c.* FROM Computer c
            INNER JOIN GenericObject g ON g.id = c.objectId
            INNER JOIN Cpu cp ON c.cpuId = cp.cpuId
            INNER JOIN Ram r ON r.ramId = c.ramId
            INNER JOIN Os o ON c.osId = o.osId
            WHERE c.modelName LIKE :key OR
            year LIKE :key OR
            hddSize LIKE :key OR
            cp.modelName LIKE :key OR
            cp.Speed LIKE :key OR
            r.modelName LIKE :key OR
            r.Size LIKE :key OR
            o.Name LIKE :key OR
            g.note LIKE :key OR
            g.tag LIKE :key OR
            g.id LIKE :key";

        $key = '%' . $key . '%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all computers
     * @return ?array   All computers, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM Computer b
            INNER JOIN GenericObject g ON g.id = b.objectId";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a computer
     * @param Computer $b
     * @throws RepositoryException If the update fails
     */
    public function update(Computer $b): void {
        $queryComputer =
            "UPDATE Computer
            SET modelName = :modelName,
            year = :year,
            hddSize = :hddSize,
            cpuId = :cpuId,
            ramId = :ramId,
            osId = :osId            
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE GenericObject
            SET note = :note,
            url = :url,
            tag = :tag
            WHERE id = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryComputer);
            $stmt->bindParam("modelName", $b->modelName);
            $stmt->bindParam("year", $b->year, PDO::PARAM_INT);
            $stmt->bindParam("hddSize", $b->hddSize);
            $stmt->bindParam("cpuId", $b->cpu->id, PDO::PARAM_INT);
            $stmt->bindParam("ramId", $b->ram->id, PDO::PARAM_INT);
            $osId = !is_null($b->os) ? $b->os->id : null;

            $stmt->bindParam("osId", $osId, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $b->objectId);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $b->note);
            $stmt->bindParam("url", $b->url);
            $stmt->bindParam("tag", $b->tag);
            $stmt->bindParam("objectId", $b->objectId);
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

            $query = "DELETE FROM Computer 
            WHERE objectId = :objectId";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
            $stmt->execute();

            $query = "DELETE FROM GenericObject 
            WHERE id = :objectId";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
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

        $os = isset($rawComputer["osId"]) ? $this->osRepository->selectById(intval($rawComputer["osId"])) : null;

        return new Computer(
            $rawComputer["objectId"],
            $rawComputer["modelName"],
            intval($rawComputer["year"]),
            $rawComputer["hddSize"] ?? null,
            $this->cpuRepository->selectById(intval($rawComputer["cpuId"])),
            $this->ramRepository->selectById(intval($rawComputer["ramId"])),
            $os,
            $rawComputer["note"] ?? null,
            $rawComputer["url"] ?? null,
            $rawComputer["tag"] ?? null,
        );
    }
}
