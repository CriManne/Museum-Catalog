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
        $this->osRepository = $osRepository;
    }

    /**
     * Insert software
     * @param Software $software    The software to insert
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Software $software): void {

        $querySoftware =
            "INSERT INTO software
                (objectId,title,osId,softwareTypeId,supportTypeId) VALUES 
                (:objectId,:title,:osId,:softwareTypeId,:supportTypeId);";

        $queryObject =
            "INSERT INTO genericobject
                (objectId,note,url,tag)
                VALUES
                (:objectId,:note,:url,:tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $software->objectId, PDO::PARAM_STR);
            $stmt->bindValue(':note', $software->note, PDO::PARAM_STR);
            $stmt->bindValue(':url', $software->url, PDO::PARAM_STR);
            $stmt->bindValue(':tag', $software->tag, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("objectId", $software->objectId, PDO::PARAM_STR);
            $stmt->bindParam("title", $software->title, PDO::PARAM_STR);
            $stmt->bindParam("osId", $software->os->id, PDO::PARAM_INT);
            $stmt->bindParam("softwareTypeId", $software->softwareType->id, PDO::PARAM_INT);
            $stmt->bindParam("supportTypeId", $software->supportType->id, PDO::PARAM_INT);

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
            INNER JOIN os o ON s.osId = o.osId
            INNER JOIN softwaretype st ON s.softwareTypeId = st.softwareTypeId
            INNER JOIN supporttype supt ON s.supportTypeId = supt.supportTypeId
            WHERE s.title LIKE :key OR
            o.Name LIKE :key OR
            st.Name LIKE :key OR
            supt.Name LIKE :key OR
            g.note LIKE :key OR
            g.tag LIKE :key OR
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
            osId = :osId,
            softwareTypeId = :softwareTypeId,
            supportTypeId = :supportTypeId
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE genericobject
            SET note = :note,
            url = :url,
            tag = :tag
            WHERE objectId = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("title", $s->title, PDO::PARAM_STR);
            $stmt->bindParam("osId", $s->os->id, PDO::PARAM_INT);
            $stmt->bindParam("softwareTypeId", $s->softwareType->id, PDO::PARAM_INT);
            $stmt->bindParam("supportTypeId", $s->supportType->id, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $s->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $s->note, PDO::PARAM_STR);
            $stmt->bindParam("url", $s->url, PDO::PARAM_STR);
            $stmt->bindParam("tag", $s->tag, PDO::PARAM_STR);
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
            $rawsoftware["title"],
            $this->osRepository->selectById(intval($rawsoftware["osId"])),
            $this->softwareTypeRepository->selectById(intval($rawsoftware["softwareTypeId"])),
            $this->supportTypeRepository->selectById(intval($rawsoftware["supportTypeId"])),
            $rawsoftware["note"] ?? null,
            $rawsoftware["url"] ?? null,
            $rawsoftware["tag"] ?? null,
        );
    }
}
