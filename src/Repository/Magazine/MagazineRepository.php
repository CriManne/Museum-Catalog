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
            "INSERT INTO Magazine
                (objectId,title,year,magazineNumber,publisherId) VALUES 
                (:objectId,:title,:year,:magazineNumber,:publisherId);";

        $queryObject =
            "INSERT INTO GenericObject
                (id,note,url,Tag)
                VALUES
                (:objectId,:note,:url,:Tag)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':objectId', $magazine->objectId);
            $stmt->bindValue(':note', $magazine->note);
            $stmt->bindValue(':url', $magazine->url);
            $stmt->bindValue(':Tag', $magazine->tag);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryMagazine);
            $stmt->bindParam("objectId", $magazine->objectId);
            $stmt->bindParam("title", $magazine->title);
            $stmt->bindParam("year", $magazine->year, PDO::PARAM_INT);
            $stmt->bindParam("magazineNumber", $magazine->magazineNumber, PDO::PARAM_INT);
            $stmt->bindParam("publisherId", $magazine->publisher->id, PDO::PARAM_INT);

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
    public function findById(string $objectId): ?Magazine {
        $query = "SELECT * FROM Magazine b 
            INNER JOIN GenericObject g ON g.id = b.objectId 
            WHERE g.id = :objectId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("objectId", $objectId);
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
        $query = "SELECT * FROM Magazine b
            INNER JOIN GenericObject g ON g.id = b.objectId 
            WHERE title LIKE :title";

        $title = '%'.$title.'%';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("title", $title);
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
        $query = "SELECT DISTINCT g.*,m.* FROM Magazine m
            INNER JOIN GenericObject g ON g.id = m.objectId
            INNER JOIN Publisher p ON m.publisherId = p.id
            WHERE m.title LIKE :key OR
            m.magazineNumber LIKE :key OR
            m.year LIKE :key OR
            p.Name LIKE :key OR
            g.note LIKE :key OR
            g.Tag LIKE :key OR
            g.id LIKE :key";

        $key = '%' . $key . '%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all magazines
     * @return ?array   All magazines, null if no result
     */
    public function selectAll(): ?array {
        $query = "SELECT * FROM Magazine b
            INNER JOIN GenericObject g ON g.id = b.objectId";

        $stmt = $this->pdo->query($query);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Update a magazine
     * @param Magazine $m
     * @throws RepositoryException If the update fails
     */
    public function update(Magazine $m): void {
        $queryMagazine =
            "UPDATE Magazine
            SET title = :title,
            year = :year,
            magazineNumber = :magazineNumber,
            publisherId = :publisherId
            WHERE objectId = :objectId";

        $queryObject =
            "UPDATE GenericObject
            SET note = :note,
            url = :url,
            Tag = :Tag
            WHERE id = :objectId";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryMagazine);
            $stmt->bindParam("title", $m->title);
            $stmt->bindParam("year", $m->year, PDO::PARAM_INT);
            $stmt->bindParam("magazineNumber", $m->magazineNumber, PDO::PARAM_INT);
            $stmt->bindParam("publisherId", $m->publisher->id, PDO::PARAM_INT);
            $stmt->bindParam("objectId", $m->objectId);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("note", $m->note);
            $stmt->bindParam("url", $m->url);
            $stmt->bindParam("Tag", $m->tag);
            $stmt->bindParam("objectId", $m->objectId);
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

            $query = "DELETE FROM Magazine
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
            $this->publisherRepository->findById(intval($rawMagazine["publisherId"])),
            $rawMagazine["note"] ?? null,
            $rawMagazine["url"] ?? null,
            $rawMagazine["Tag"] ?? null,
        );
    }
}
