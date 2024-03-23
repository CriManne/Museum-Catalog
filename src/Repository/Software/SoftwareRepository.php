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
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Software $software): void {

        $querySoftware =
            "INSERT INTO software
                (objectId,title,OsID,SoftwareTypeID,SupportTypeID) VALUES 
                (:objectId,:title,:OsID,:SoftwareTypeID,:SupportTypeID);";

        $queryObject =
            "INSERT INTO genericobject
                (objectId,Note,Url,Tag)
                VALUES
                (:objectId,:Note,:Url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $software->objectId, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $software->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $software->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $software->Tag, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("objectId", $software->objectId, PDO::PARAM_STR);
            $stmt->bindParam("title", $software->title, PDO::PARAM_STR);
            $stmt->bindParam("OsID", $software->Os->OsID, PDO::PARAM_INT);
            $stmt->bindParam("SoftwareTypeID", $software->SoftwareType->SoftwareTypeID, PDO::PARAM_INT);
            $stmt->bindParam("SupportTypeID", $software->SupportType->SupportTypeID, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the software with id: {" . $software->objectId . "}");
        }
    }

    /**
     * Select software by id
     * @param string $objectId  The object id to select
     * @return ?Software    The software selected, null if not found
     */
    public function selectById(string $objectId): ?Software {
        $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.objectId = software.objectId 
            WHERE g.objectId = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
        $stmt->execute();
        $software = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($software) {
            return $this->returnMappedObject($software);
        }
        return null;
    }

    /**
     * Select software by title
     * @param string $title     The software title to select
     * @return ?Software    The software selected, null if not found
     */
    public function selectBytitle(string $title): ?Software {
        $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.objectId = software.objectId 
            WHERE title = :title";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("title", $title, PDO::PARAM_STR);
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
            INNER JOIN genericobject g ON g.objectId = s.objectId 
            INNER JOIN os o ON s.OsID = o.OsID
            INNER JOIN softwaretype st ON s.SoftwareTypeID = st.SoftwareTypeID
            INNER JOIN supporttype supt ON s.SupportTypeID = supt.SupportTypeID
            WHERE s.title LIKE :key OR
            o.Name LIKE :key OR
            st.Name LIKE :key OR
            supt.Name LIKE :key OR
            g.Note LIKE :key OR
            g.Tag LIKE :key OR
            g.objectId LIKE :key";

        $key = '%' . $key . '%';

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
            INNER JOIN genericobject g ON g.objectId = software.objectId";

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
            SET title = :title,
            OsID = :OsID,
            SoftwareTypeID = :SoftwareTypeID,
            SupportTypeID = :SupportTypeID
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag
            WHERE objectId = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("title", $s->title, PDO::PARAM_STR);
            $stmt->bindParam("OsID", $s->Os->OsID, PDO::PARAM_INT);
            $stmt->bindParam("SoftwareTypeID", $s->SoftwareType->SoftwareTypeID, PDO::PARAM_INT);
            $stmt->bindParam("SupportTypeID", $s->SupportType->SupportTypeID, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $s->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("Note", $s->Note, PDO::PARAM_STR);
            $stmt->bindParam("Url", $s->Url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $s->Tag, PDO::PARAM_STR);
            $stmt->bindParam("objectId", $s->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the software with id: {" . $s->objectId . "}");
        }
    }

    /**
     * Delete a software
     * @param string $objectId  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM software
            WHERE objectId = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
            $stmt->execute();

            $query = "DELETE FROM genericobject
            WHERE objectId = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the software with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Software from an array
     * @param array $rawSoftware    The raw software object
     * @return Software The new instance of software with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawsoftware): Software {
        return new Software(
            $rawsoftware["objectId"],
            $rawsoftware["Note"] ?? null,
            $rawsoftware["Url"] ?? null,
            $rawsoftware["Tag"] ?? null,
            $rawsoftware["title"],
            $this->OsRepository->selectById(intval($rawsoftware["OsID"])),
            $this->softwareTypeRepository->selectById(intval($rawsoftware["SoftwareTypeID"])),
            $this->supportTypeRepository->selectById(intval($rawsoftware["SupportTypeID"]))
        );
    }
}
