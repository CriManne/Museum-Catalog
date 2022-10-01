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
                (ObjectID,ModelName,PeripheralTypeID) VALUES 
                (:ObjectID,:ModelName,:PeripheralTypeID);";

        $queryObject =
            "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag)
                VALUES
                (:ObjectID,:Note,:Url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':ObjectID', $peripheral->ObjectID, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $peripheral->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $peripheral->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $peripheral->Tag, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("ObjectID", $peripheral->ObjectID, PDO::PARAM_STR);
            $stmt->bindParam("ModelName", $peripheral->ModelName, PDO::PARAM_STR);
            $stmt->bindParam("PeripheralTypeID", $peripheral->PeripheralType->PeripheralTypeID, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the peripheral with id: {" . $peripheral->ObjectID . "}");
        }
    }

    /**
     * Select peripheral by id
     * @param string $ObjectID  The object id to select
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function selectById(string $ObjectID): ?Peripheral {
        $query = "SELECT * FROM peripheral p 
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID 
            WHERE g.ObjectID = :ObjectID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
        $stmt->execute();
        $peripheral = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($peripheral) {
            return $this->returnMappedObject($peripheral);
        }
        return null;
    }

    /**
     * Select peripheral by ModelName
     * @param string $ModelName     The peripheral ModelName to select
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function selectByModelName(string $ModelName): ?Peripheral {
        $query = "SELECT * FROM peripheral p
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID 
            WHERE ModelName LIKE :ModelName";
        
        $ModelName = '%'.$ModelName.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ModelName", $ModelName, PDO::PARAM_STR);
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
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID
            INNER JOIN peripheraltype pt ON p.PeripheralTypeID = pt.PeripheralTypeID
            WHERE p.ModelName LIKE :key OR
            pt.Name LIKE :key OR
            g.Note LIKE :key OR
            g.Tag LIKE :key OR
            g.ObjectID LIKE :key";

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
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID";

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
            SET ModelName = :ModelName,
            PeripheralTypeID = :PeripheralTypeID
            WHERE ObjectID = :ObjectID";

        $queryObject =
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag
            WHERE ObjectID = :ObjectID";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("ModelName", $p->ModelName, PDO::PARAM_STR);
            $stmt->bindParam("PeripheralTypeID", $p->PeripheralType->PeripheralTypeID, PDO::PARAM_INT);
            $stmt->bindParam("ObjectID", $p->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("Note", $p->Note, PDO::PARAM_STR);
            $stmt->bindParam("Url", $p->Url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $p->Tag, PDO::PARAM_STR);
            $stmt->bindParam("ObjectID", $p->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the peripheral with id: {" . $p->ObjectID . "}");
        }
    }

    /**
     * Delete a peripheral
     * @param string $ObjectID  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $ObjectID): void {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM peripheral
            WHERE ObjectID = :ObjectID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $query = "DELETE FROM genericobject
            WHERE ObjectID = :ObjectID";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the peripheral with id: {" . $ObjectID . "}");
        }
    }

    /**
     * Return a new instance of Peripheral from an array
     * @param array $rawPeripheral    The raw peripheral object
     * @return Peripheral The new instance of peripheral with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawPeripheral): Peripheral {
        return new Peripheral(
            $rawPeripheral["ObjectID"],
            $rawPeripheral["Note"] ?? null,
            $rawPeripheral["Url"] ?? null,
            $rawPeripheral["Tag"] ?? null,
            $rawPeripheral["ModelName"],
            $this->peripheralTypeRepository->selectById(intval($rawPeripheral["PeripheralTypeID"]))
        );
    }
}
