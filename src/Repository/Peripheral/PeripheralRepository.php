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
     * @return Peripheral         The peripheral inserted
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Peripheral $peripheral): Peripheral {

        $queryPeripheral =
            "INSERT INTO peripheral
                (ObjectID,ModelName,PeripheralTypeID) VALUES 
                (:ObjectID,:ModelName,:PeripheralTypeID);";

        $queryObject =
            "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag,Active,Erased)
                VALUES
                (:ObjectID,:Note,:Url,:Tag,:Active,:Erased)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':ObjectID', $peripheral->ObjectID, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $peripheral->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $peripheral->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $peripheral->Tag, PDO::PARAM_STR);
            $stmt->bindValue(':Active', $peripheral->Active, PDO::PARAM_STR);
            $stmt->bindValue(':Erased', $peripheral->Erased, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryPeripheral);
            $stmt->bindParam("ObjectID", $peripheral->ObjectID, PDO::PARAM_STR);
            $stmt->bindParam("ModelName", $peripheral->ModelName, PDO::PARAM_STR);
            $stmt->bindParam("PeripheralTypeID", $peripheral->PeripheralType->PeripheralTypeID, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
            return $peripheral;
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the peripheral with id: {" . $peripheral->ObjectID . "}");
        }
    }

    /**
     * Select peripheral by id
     * @param string $ObjectID  The object id to select
     * @param ?bool $showErased
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function selectById(string $ObjectID, ?bool $showErased = false): ?Peripheral {
        $query = "SELECT * FROM peripheral p 
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID 
            WHERE g.ObjectID = :ObjectID";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

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
     * @param ?bool $showErased
     * @return ?Peripheral    The peripheral selected, null if not found
     */
    public function selectByModelName(string $ModelName, ?bool $showErased = false): ?Peripheral {
        $query = "SELECT * FROM peripheral p
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID 
            WHERE ModelName LIKE Concat('%',:ModelName,'%')";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

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
     * Select all peripherals
     * @param ?bool $showErased
     * @return ?array   All peripherals, null if no result
     */
    public function selectAll(?bool $showErased = false): ?array {
        $query = "SELECT * FROM peripheral p
            INNER JOIN genericobject g ON g.ObjectID = p.ObjectID";

        if (isset($showErased)) {
            $query .= " WHERE Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->query($query);

        $arr_peripheral = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);

        return $arr_peripheral;
    }

    /**
     * Update a peripheral
     * @param Peripheral $p   The peripheral to update
     * @return Peripheral     The peripheral updated
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
            Tag = :Tag,
            Active = :Active,
            Erased = :Erased
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
            $stmt->bindParam("Active", $p->Active, PDO::PARAM_STR);
            $stmt->bindParam("Erased", $p->Erased, PDO::PARAM_STR);
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
        $query =
            "UPDATE genericobject
            SET Erased = NOW()
            WHERE ObjectID = :ObjectID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
        try {
            $stmt->execute();
        } catch (PDOException) {
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
            $rawPeripheral["Note"],
            $rawPeripheral["Url"],
            $rawPeripheral["Tag"],
            strval($rawPeripheral["Active"]),
            $rawPeripheral["Erased"],
            $rawPeripheral["ModelName"],
            $this->peripheralTypeRepository->selectById($rawPeripheral["PeripheralTypeID"])
        );
    }
}
