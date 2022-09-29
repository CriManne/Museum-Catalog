<?php

declare(strict_types=1);

namespace App\Repository\Software;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Software\Software;
use App\Repository\Computer\OsRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Repository\Software\SoftwareTypeRepository;
use PDO;
use PDOException;

class SoftwareRepository extends GenericRepository {

    public SoftwareTypeRepository $softwareTypeRepository;
    public SupportTypeRepository $supportTypeRepository;
    public OsRepository $osRepository;

    public function __construct(
        PDO $pdo,
        SoftwareTypeRepository $softwareTypeRepository,
        SupportTypeRepository $supportTypeRepository,
        OsRepository $osRepository
    ) {
        parent::__construct($pdo);
        $this->softwareTypeRepository = $softwareTypeRepository;
        $this->supportTypeRepository = $supportTypeRepository;
        $this->OsRepository = $osRepository;
    }

    /**
     * Insert software
     * @param Software $software    The software to insert
     * @return Software         The software inserted
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Software $software): Software {

        $querySoftware =
            "INSERT INTO software
                (ObjectID,Title,OsID,SoftwareTypeID,SupportTypeID) VALUES 
                (:ObjectID,:Title,:OsID,:SoftwareTypeID,:SupportTypeID);";

        $queryObject =
            "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag)
                VALUES
                (:ObjectID,:Note,:Url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':ObjectID', $software->ObjectID, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $software->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $software->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $software->Tag, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("ObjectID", $software->ObjectID, PDO::PARAM_STR);
            $stmt->bindParam("Title", $software->Title, PDO::PARAM_STR);
            $stmt->bindParam("OsID", $software->Os->OsID, PDO::PARAM_INT);
            $stmt->bindParam("SoftwareTypeID", $software->SoftwareType->SoftwareTypeID, PDO::PARAM_INT);
            $stmt->bindParam("SupportTypeID", $software->SupportType->SupportTypeID, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
            return $software;
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the software with id: {" . $software->ObjectID . "}");
        }
    }

    /**
     * Select software by id
     * @param string $ObjectID  The object id to select
     * @return ?Software    The software selected, null if not found
     */
    public function selectById(string $ObjectID): ?Software {
        $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID 
            WHERE g.ObjectID = :ObjectID";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
        $stmt->execute();
        $software = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($software) {
            return $this->returnMappedObject($software);
        }
        return null;
    }

    /**
     * Select software by title
     * @param string $Title     The software title to select
     * @return ?Software    The software selected, null if not found
     */
    public function selectByTitle(string $Title): ?Software {
        $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID 
            WHERE Title = :Title";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Title", $Title, PDO::PARAM_STR);
        $stmt->execute();
        $software = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($software) {
            return $this->returnMappedObject($software);
        }
        return null;
    }

    /**
     * Select software by key
     * @param string $key     The key given
     * @return array    Software(s) selected, empty array if no result
     */
    public function selectByKey(string $key): array {
        $query = "SELECT DISTINCT g.*,s.* FROM software s
            INNER JOIN genericobject g ON g.ObjectID = s.ObjectID 
            INNER JOIN os o ON s.OsID = o.OsID
            INNER JOIN softwaretype st ON s.SoftwareTypeID = st.SoftwareTypeID
            INNER JOIN supporttype supt ON s.SupportTypeID = supt.SupportTypeID
            WHERE s.Title LIKE :key OR
            o.Name LIKE :key OR
            st.Name LIKE :key OR
            supt.Name LIKE :key OR
            g.Note LIKE :key OR
            g.Tag LIKE :key";

        $key = '%'.$key.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        $stmt->execute();
        $arr_software = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_software;
    }

    /**
     * Select all software
     * @return ?array   All software, null if no results
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM software
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID";

        $stmt = $this->pdo->query($query);

        $arr_software = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_software;
    }

    /**
     * Update a software
     * @param Software $s   The software to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Software $s): void {
        $querySoftware =
            "UPDATE software
            SET Title = :Title,
            OsID = :OsID,
            SoftwareTypeID = :SoftwareTypeID,
            SupportTypeID = :SupportTypeID
            WHERE ObjectID = :ObjectID";

        $queryObject =
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag
            WHERE ObjectID = :ObjectID";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("Title", $s->Title, PDO::PARAM_STR);
            $stmt->bindParam("OsID", $s->Os->OsID, PDO::PARAM_INT);
            $stmt->bindParam("SoftwareTypeID", $s->SoftwareType->SoftwareTypeID, PDO::PARAM_INT);
            $stmt->bindParam("SupportTypeID", $s->SupportType->SupportTypeID, PDO::PARAM_INT);
            $stmt->bindParam("ObjectID", $s->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("Note", $s->Note, PDO::PARAM_STR);
            $stmt->bindParam("Url", $s->Url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $s->Tag, PDO::PARAM_STR);
            $stmt->bindParam("ObjectID", $s->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the software with id: {" . $s->ObjectID . "}");
        }
    }

    /**
     * Delete a software
     * @param string $ObjectID  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $ObjectID): void {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM software
            WHERE ObjectID = :ObjectID;";
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $query = "DELETE FROM genericobject
            WHERE ObjectID = :ObjectID;";
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the software with id: {" . $ObjectID . "}");
        }
    }

    /**
     * Return a new instance of Software from an array
     * @param array $rawSoftware    The raw software object
     * @return Software The new instance of software with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawsoftware): Software {
        return new Software(
            $rawsoftware["ObjectID"],
            $rawsoftware["Note"] ?? null,
            $rawsoftware["Url"] ?? null,
            $rawsoftware["Tag"] ?? null,
            $rawsoftware["Title"],
            $this->OsRepository->selectById(intval($rawsoftware["OsID"]),null),
            $this->softwareTypeRepository->selectById(intval($rawsoftware["SoftwareTypeID"]),null),
            $this->supportTypeRepository->selectById(intval($rawsoftware["SupportTypeID"]),null)
        );
    }
}
