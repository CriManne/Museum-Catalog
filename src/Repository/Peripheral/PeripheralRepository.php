<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;

use App\Model\Peripheral\Peripheral;

use PDO;
use PDOException;

class PeripheralRepository extends GenericRepository
{
    public PeripheralTypeRepository $peripheralTypeRepository;

    public function __construct(
        PDO                      $pdo,
        PeripheralTypeRepository $peripheralTypeRepository
    )
    {
        parent::__construct($pdo);
        $this->peripheralTypeRepository = $peripheralTypeRepository;
    }

    /**
     * Insert Peripheral
     * @param Peripheral $peripheral The peripheral to save
     * @throws RepositoryException  If the save fails
     */
    public function save(Peripheral $peripheral): void
    {

        $queryPeripheral =
            "INSERT INTO Peripheral
                (objectId,modelName,peripheralTypeId) VALUES 
                (:objectId,:modelName,:peripheralTypeId);";

        $queryObject =
            "INSERT INTO GenericObject
                (id,note,url,tag)
                VALUES
                (:objectId,:note,:url,:tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $peripheral->objectId);
            $stmt->bindValue(':note', $peripheral->note);
            $stmt->bindValue(':url', $peripheral->url);
            $stmt->bindValue(':tag', $peripheral->tag);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("objectId", $peripheral->objectId);
            $stmt->bindParam("modelName", $peripheral->modelName);
            $stmt->bindParam("peripheralTypeId", $peripheral->peripheralType->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while saveing the peripheral with id: {" . $peripheral->objectId . "}");
        }
    }

    /**
     * Select peripheral by id
     * @param string $objectId The object id to select
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function findById(string $objectId): ?Peripheral
    {
        $query = "SELECT * FROM Peripheral p 
            INNER JOIN GenericObject g ON g.id = p.objectId 
            WHERE g.id = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId);
        $stmt->execute();
        $peripheral = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheral) {
            return $this->returnMappedObject($peripheral);
        }
        return null;
    }

    /**
     * Select peripheral by modelName
     * @param string $modelName The peripheral modelName to select
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function findByName(string $modelName): ?Peripheral
    {
        $query = "SELECT * FROM Peripheral p
            INNER JOIN GenericObject g ON g.id = p.objectId 
            WHERE modelName LIKE :modelName";

        $modelName = '%' . $modelName . '%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("modelName", $modelName);
        $stmt->execute();
        $peripheral = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheral) {
            return $this->returnMappedObject($peripheral);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array The peripherals, empty array if no result
     */
    public function findByQuery(string $key): array
    {
        $query = "SELECT DISTINCT g.*,p.* FROM Peripheral p
            INNER JOIN GenericObject g ON g.id = p.objectId
            INNER JOIN PeripheralType pt ON p.peripheralTypeId = pt.id
            WHERE p.modelName LIKE :key OR
            pt.name LIKE :key OR
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
     * Select all peripherals
     * @return ?array   All peripherals, null if no result
     */
    public function find(): ?array
    {
        $query = "SELECT * FROM Peripheral p
            INNER JOIN GenericObject g ON g.id = p.objectId";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a peripheral
     * @param Peripheral $p The peripheral to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Peripheral $p): void
    {
        $queryPeripheral =
            "UPDATE Peripheral
            SET modelName = :modelName,
            peripheralTypeId = :peripheralTypeId
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE GenericObject
            SET note = :note,
            url = :url,
            tag = :tag
            WHERE id = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("modelName", $p->modelName);
            $stmt->bindParam("peripheralTypeId", $p->peripheralType->id, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $p->objectId);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $p->note);
            $stmt->bindParam("url", $p->url);
            $stmt->bindParam("tag", $p->tag);
            $stmt->bindParam("objectId", $p->objectId);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the peripheral with id: {" . $p->objectId . "}");
        }
    }

    /**
     * Delete a peripheral
     * @param string $objectId The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void
    {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM Peripheral
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
            throw new RepositoryException("Error while deleting the peripheral with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Peripheral from an array
     * @param array $rawPeripheral The raw peripheral object
     * @return Peripheral The new instance of peripheral with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawPeripheral): Peripheral
    {
        return new Peripheral(
            $rawPeripheral["objectId"],
            $rawPeripheral["modelName"],
            $this->peripheralTypeRepository->findById(intval($rawPeripheral["peripheralTypeId"])),
            $rawPeripheral["note"] ?? null,
            $rawPeripheral["url"] ?? null,
            $rawPeripheral["tag"] ?? null,
        );
    }
}
