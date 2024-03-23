<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;

use App\Model\Peripheral\Peripheral;

use App\Repository\Peripheral\PeripheralTypeRepository;

use PDO;
use PDOException;

class PeripheralRepository extends GenericRepository {

    public PeripheralTypeRepository $peripheralTypeRepository;

    public function __construct(
        PDO $pdo,
        PeripheralTypeRepository $peripheralTypeRepository
    ) {
        parent::__construct($pdo);
        $this->peripheralTypeRepository = $peripheralTypeRepository;
    }

    /**
     * Insert Peripheral
     * @param Peripheral $peripheral    The peripheral to insert
     * @throws RepositoryException  If the insert fails          
     */
    public function insert(Peripheral $peripheral): void {

        $queryPeripheral =
            "INSERT INTO peripheral
                (objectId,modelName,peripheralTypeId) VALUES 
                (:objectId,:modelName,:peripheralTypeId);";

        $queryObject =
            "INSERT INTO genericobject
                (objectId,note,url,tag)
                VALUES
                (:objectId,:note,:url,:tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $peripheral->objectId, PDO::PARAM_STR);
            $stmt->bindValue(':note', $peripheral->note, PDO::PARAM_STR);
            $stmt->bindValue(':url', $peripheral->url, PDO::PARAM_STR);
            $stmt->bindValue(':tag', $peripheral->tag, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("objectId", $peripheral->objectId, PDO::PARAM_STR);
            $stmt->bindParam("modelName", $peripheral->modelName, PDO::PARAM_STR);
            $stmt->bindParam("peripheralTypeId", $peripheral->PeripheralType->peripheralTypeId, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the peripheral with id: {" . $peripheral->objectId . "}");
        }
    }

    /**
     * Select peripheral by id
     * @param string $objectId  The object id to select
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function selectById(string $objectId): ?Peripheral {
        $query = "SELECT * FROM peripheral p 
            INNER JOIN genericobject g ON g.objectId = p.objectId 
            WHERE g.objectId = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
        $stmt->execute();
        $peripheral = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheral) {
            return $this->returnMappedObject($peripheral);
        }
        return null;
    }

    /**
     * Select peripheral by modelName
     * @param string $modelName     The peripheral modelName to select
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function selectBymodelName(string $modelName): ?Peripheral {
        $query = "SELECT * FROM peripheral p
            INNER JOIN genericobject g ON g.objectId = p.objectId 
            WHERE modelName LIKE :modelName";
        
        $modelName = '%'.$modelName.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("modelName", $modelName, PDO::PARAM_STR);
        $stmt->execute();
        $peripheral = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheral) {
            return $this->returnMappedObject($peripheral);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key     The key given
     * @return array The peripherals, empty array if no result
     */
    public function selectByKey(string $key): array {
        $query = "SELECT DISTINCT g.*,p.* FROM peripheral p
            INNER JOIN genericobject g ON g.objectId = p.objectId
            INNER JOIN peripheraltype pt ON p.peripheralTypeId = pt.peripheralTypeId
            WHERE p.modelName LIKE :key OR
            pt.Name LIKE :key OR
            g.note LIKE :key OR
            g.tag LIKE :key OR
            g.objectId LIKE :key";

        $key = '%' . $key . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();

        $arr_peripheral = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_peripheral;
    }

    /**
     * Select all peripherals
     * @return ?array   All peripherals, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM peripheral p
            INNER JOIN genericobject g ON g.objectId = p.objectId";

        $stmt = $this->pdo->query($query);

        $arr_peripheral = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_peripheral;
    }

    /**
     * Update a peripheral
     * @param Peripheral $p   The peripheral to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Peripheral $p): void {
        $queryPeripheral =
            "UPDATE peripheral
            SET modelName = :modelName,
            peripheralTypeId = :peripheralTypeId
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE genericobject
            SET note = :note,
            url = :url,
            tag = :tag
            WHERE objectId = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("modelName", $p->modelName, PDO::PARAM_STR);
            $stmt->bindParam("peripheralTypeId", $p->PeripheralType->peripheralTypeId, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $p->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $p->note, PDO::PARAM_STR);
            $stmt->bindParam("url", $p->url, PDO::PARAM_STR);
            $stmt->bindParam("tag", $p->tag, PDO::PARAM_STR);
            $stmt->bindParam("objectId", $p->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the peripheral with id: {" . $p->objectId . "}");
        }
    }

    /**
     * Delete a peripheral
     * @param string $objectId  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM peripheral
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
            throw new RepositoryException("Error while deleting the peripheral with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Peripheral from an array
     * @param array $rawPeripheral    The raw peripheral object
     * @return Peripheral The new instance of peripheral with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawPeripheral): Peripheral {
        return new Peripheral(
            $rawPeripheral["objectId"],
            $rawPeripheral["modelName"],
            $this->peripheralTypeRepository->selectById(intval($rawPeripheral["peripheralTypeId"])),
            $rawPeripheral["note"] ?? null,
            $rawPeripheral["url"] ?? null,
            $rawPeripheral["tag"] ?? null,
        );
    }
}
