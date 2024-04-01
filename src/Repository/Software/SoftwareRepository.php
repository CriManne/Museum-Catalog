<?php

declare(strict_types=1);

namespace App\Repository\Software;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Software\Software;
use App\Repository\Computer\OsRepository;
use PDO;
use PDOException;

class SoftwareRepository extends GenericRepository
{
    public SoftwareTypeRepository $softwareTypeRepository;
    public SupportTypeRepository $supportTypeRepository;
    public OsRepository $osRepository;

    public function __construct(
        PDO                    $pdo,
        SoftwareTypeRepository $softwareTypeRepository,
        SupportTypeRepository  $supportTypeRepository,
        OsRepository           $osRepository
    )
    {
        parent::__construct($pdo);
        $this->softwareTypeRepository = $softwareTypeRepository;
        $this->supportTypeRepository = $supportTypeRepository;
        $this->osRepository = $osRepository;
    }

    /**
     * Insert software
     * @param Software $software The software to save
     * @throws RepositoryException  If the save fails         *
     */
    public function save(Software $software): void
    {

        $querySoftware =
            "INSERT INTO Software
                (objectId,title,osId,softwareTypeId,supportTypeId) VALUES 
                (:objectId,:title,:osId,:softwareTypeId,:supportTypeId);";

        $queryObject =
            "INSERT INTO GenericObject
                (id,note,url,tag)
                VALUES
                (:objectId,:note,:url,:tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $software->objectId);
            $stmt->bindValue(':note', $software->note);
            $stmt->bindValue(':url', $software->url);
            $stmt->bindValue(':tag', $software->tag);
            $stmt->execute();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("objectId", $software->objectId);
            $stmt->bindParam("title", $software->title);
            $stmt->bindParam("osId", $software->os->id, PDO::PARAM_INT);
            $stmt->bindParam("softwareTypeId", $software->softwareType->id, PDO::PARAM_INT);
            $stmt->bindParam("supportTypeId", $software->supportType->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while saveing the software with id: {" . $software->objectId . "}");
        }
    }

    /**
     * Select software by id
     * @param string $objectId The object id to select
     * @return ?Software    The software selected, null if not found
     */
    public function findById(string $objectId): ?Software
    {
        $query = "SELECT * FROM Software s
            INNER JOIN GenericObject g ON g.id = s.objectId 
            WHERE g.id = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId);
        $stmt->execute();
        $software = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($software) {
            return $this->returnMappedObject($software);
        }
        return null;
    }

    /**
     * Select software by title
     * @param string $title The software title to select
     * @return ?Software    The software selected, null if not found
     */
    public function findByTitle(string $title): ?Software
    {
        $query = "SELECT * FROM Software s
            INNER JOIN GenericObject g ON g.id = s.objectId 
            WHERE title = :title";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("title", $title);
        $stmt->execute();
        $software = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($software) {
            return $this->returnMappedObject($software);
        }
        return null;
    }

    /**
     * Select software by key
     * @param string $key The key given
     * @return array    Software(s) selected, empty array if no result
     */
    public function findByQuery(string $key): array
    {
        $query = "SELECT DISTINCT g.*,s.* FROM Software s
            INNER JOIN GenericObject g ON g.id = s.objectId 
            INNER JOIN Os o ON s.osId = o.id
            INNER JOIN SoftwareType st ON s.softwareTypeId = st.id
            INNER JOIN SupportType supt ON s.supportTypeId = supt.id
            WHERE s.title LIKE :key OR
            o.name LIKE :key OR
            st.name LIKE :key OR
            supt.name LIKE :key OR
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
     * Select all software
     * @return ?array   All software, null if no results
     */
    public function find(): ?array
    {
        $query = "SELECT * FROM Software s
            INNER JOIN GenericObject g ON g.id = s.objectId";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a software
     * @param Software $s The software to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Software $s): void
    {
        $querySoftware =
            "UPDATE Software
            SET title = :title,
            osId = :osId,
            softwareTypeId = :softwareTypeId,
            supportTypeId = :supportTypeId
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE GenericObject
            SET note = :note,
            url = :url,
            tag = :tag
            WHERE id = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($querySoftware);
            $stmt->bindParam("title", $s->title);
            $stmt->bindParam("osId", $s->os->id, PDO::PARAM_INT);
            $stmt->bindParam("softwareTypeId", $s->softwareType->id, PDO::PARAM_INT);
            $stmt->bindParam("supportTypeId", $s->supportType->id, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $s->objectId);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $s->note);
            $stmt->bindParam("url", $s->url);
            $stmt->bindParam("tag", $s->tag);
            $stmt->bindParam("objectId", $s->objectId);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the software with id: {" . $s->objectId . "}");
        }
    }

    /**
     * Delete a software
     * @param string $objectId The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void
    {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM Software
            WHERE objectId = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
            $stmt->execute();

            $query = "DELETE FROM GenericObject
            WHERE id = :objectId;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam("objectId", $objectId);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while deleting the software with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Software from an array
     * @param array $rawsoftware
     * @return Software The new instance of software with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawsoftware): Software
    {
        return new Software(
            $rawsoftware["objectId"],
            $rawsoftware["title"],
            $this->osRepository->findById(intval($rawsoftware["osId"])),
            $this->softwareTypeRepository->findById(intval($rawsoftware["softwareTypeId"])),
            $this->supportTypeRepository->findById(intval($rawsoftware["supportTypeId"])),
            $rawsoftware["note"] ?? null,
            $rawsoftware["url"] ?? null,
            $rawsoftware["tag"] ?? null,
        );
    }
}
