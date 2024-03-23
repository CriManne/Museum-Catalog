<?php

declare(strict_types=1);

namespace App\Repository\Magazine;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;

use App\Model\Magazine\Magazine;

use App\Repository\Book\PublisherRepository;

use PDO;
use PDOException;

class MagazineRepository extends GenericRepository {

    public PublisherRepository $publisherRepository;

    public function __construct(
        PDO $pdo,
        PublisherRepository $publisherRepository
    ) {
        parent::__construct($pdo);
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Insert Magazine
     * @param Magazine $magazine    The magazine to insert
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Magazine $magazine): void {

        $queryMagazine =
            "INSERT INTO magazine
                (objectId,title,year,magazineNumber,publisherId) VALUES 
                (:objectId,:title,:year,:magazineNumber,:publisherId);";

        $queryObject =
            "INSERT INTO genericobject
                (objectId,note,url,Tag)
                VALUES
                (:objectId,:note,:url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $magazine->objectId, PDO::PARAM_STR);
            $stmt->bindValue(':note', $magazine->note, PDO::PARAM_STR);
            $stmt->bindValue(':url', $magazine->url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $magazine->Tag, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryMagazine);
            $stmt->bindParam("objectId", $magazine->objectId, PDO::PARAM_STR);
            $stmt->bindParam("title", $magazine->title, PDO::PARAM_STR);
            $stmt->bindParam("year", $magazine->year, PDO::PARAM_INT);
            $stmt->bindParam("magazineNumber", $magazine->magazineNumber, PDO::PARAM_INT);
            $stmt->bindParam("publisherId", $magazine->Publisher->publisherId, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the magazine with id: {" . $magazine->objectId . "}");
        }
    }

    /**
     * Select magazine by id
     * @param string $objectId  The object id to select
     * @return ?Magazine    The magazine selected, null if not found
     */
    public function selectById(string $objectId): ?Magazine {
        $query = "SELECT * FROM magazine b 
            INNER JOIN genericobject g ON g.objectId = b.objectId 
            WHERE g.objectId = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId, PDO::PARAM_STR);
        $stmt->execute();
        $magazine = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($magazine) {
            return $this->returnMappedObject($magazine);
        }
        return null;
    }

    /**
     * Select magazine by title
     * @param string $title     The magazine title to select
     * @return ?Magazine    The magazine selected, null if not found
     */
    public function selectByTitle(string $title): ?Magazine {
        $query = "SELECT * FROM magazine b
            INNER JOIN genericobject g ON g.objectId = b.objectId 
            WHERE title LIKE :title";

        $title = '%'.$title.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("title", $title, PDO::PARAM_STR);
        $stmt->execute();
        $magazine = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($magazine) {
            return $this->returnMappedObject($magazine);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array   All magazines, empty array if no result
     */
    public function selectByKey(string $key): array {
        $query = "SELECT DISTINCT g.*,m.* FROM magazine m
            INNER JOIN genericobject g ON g.objectId = m.objectId
            INNER JOIN publisher p ON m.publisherId = p.publisherId
            WHERE m.title LIKE :key OR
            m.magazineNumber LIKE :key OR
            m.year LIKE :key OR
            p.Name LIKE :key OR
            g.note LIKE :key OR
            g.Tag LIKE :key OR
            g.objectId LIKE :key";

        $key = '%' . $key . '%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);

        $stmt->execute();

        $arr_magazine = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_magazine;
    }

    /**
     * Select all magazines
     * @return ?array   All magazines, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM magazine b
            INNER JOIN genericobject g ON g.objectId = b.objectId";

        $stmt = $this->pdo->query($query);

        $arr_magazine = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_magazine;
    }

    /**
     * Update a magazine
     * @param Magazine $s   The magazine to update
     * @throws RepositoryException  If the update fails
     */
    public function update(Magazine $m): void {
        $queryMagazine =
            "UPDATE magazine
            SET title = :title,
            year = :year,
            magazineNumber = :magazineNumber,
            publisherId = :publisherId
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE genericobject
            SET note = :note,
            url = :url,
            Tag = :Tag
            WHERE objectId = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryMagazine);
            $stmt->bindParam("title", $m->title, PDO::PARAM_STR);
            $stmt->bindParam("year", $m->year, PDO::PARAM_INT);
            $stmt->bindParam("magazineNumber", $m->magazineNumber, PDO::PARAM_INT);
            $stmt->bindParam("publisherId", $m->Publisher->publisherId, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $m->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $m->note, PDO::PARAM_STR);
            $stmt->bindParam("url", $m->url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $m->Tag, PDO::PARAM_STR);
            $stmt->bindParam("objectId", $m->objectId, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the magazine with id: {" . $m->objectId . "}");
        }
    }

    /**
     * Delete a magazine
     * @param string $objectId  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $objectId): void {
        try {
            $this->pdo->beginTransaction();

            $query = "DELETE FROM magazine
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
            throw new RepositoryException("Error while deleting the magazine with id: {" . $objectId . "}");
        }
    }

    /**
     * Return a new instance of Magazine from an array
     * @param array $rawMagazine    The raw magazine object
     * @return Magazine The new instance of magazine with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawMagazine): Magazine {
        return new Magazine(
            $rawMagazine["objectId"],
            $rawMagazine["title"],
            intval($rawMagazine["year"]),
            intval($rawMagazine["magazineNumber"]),
            $this->publisherRepository->selectById(intval($rawMagazine["publisherId"])),
            $rawMagazine["note"] ?? null,
            $rawMagazine["url"] ?? null,
            $rawMagazine["Tag"] ?? null,
        );
    }
}
