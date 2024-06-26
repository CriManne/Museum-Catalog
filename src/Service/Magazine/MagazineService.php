<?php

declare(strict_types=1);

namespace App\Service\Magazine;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\DatabaseException;
use App\Exception\ServiceException;
use App\Models\GenericObject;
use App\Models\IArtifact;
use App\Models\Magazine\Magazine;
use App\Plugins\DB\DB;
use App\Repository\Book\PublisherRepository;
use App\Repository\GenericObjectRepository;
use App\Repository\Magazine\MagazineRepository;
use App\Service\IArtifactService;
use Throwable;

class MagazineService implements IArtifactService
{
    public function __construct(
        protected GenericObjectRepository $genericObjectRepository,
        protected MagazineRepository  $magazineRepository,
        protected PublisherRepository $publisherRepository
    )
    {
    }

    /**
     * Insert magazine
     *
     * @param Magazine $m The magazine to save
     *
     * @throws RepositoryException If the save fails
     * @throws ServiceException If the title is already used
     * @throws Throwable
     * @throws DatabaseException
     */
    public function save(IArtifact $m): void
    {
        $magazine = $this->magazineRepository->findFirst(
            new FetchParams(
                conditions: "title = :title",
                bind: ["title" => $m->title]
            )
        );

        if ($magazine) {
            throw new ServiceException("Magazine title already used!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->save($m->genericObject);
            $this->magazineRepository->save($m);
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Select by id
     *
     * @param string $id The id to select
     *
     * @return Magazine The magazine selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(string $id): Magazine
    {
        $magazine = $this->magazineRepository->findById($id);
        if (!$magazine) {
            throw new ServiceException("Magazine not found");
        }

        return $magazine;
    }

    /**
     * Select by key
     *
     * @param string $key The key given
     *
     * @return array Magazines selected, empty array if no result
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->magazineRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the magazines
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->magazineRepository->find();
    }

    /**
     * Update a Magazine
     *
     * @param Magazine $m The Magazine to update
     *
     * @throws DatabaseException
     * @throws RepositoryException If the update fails
     * @throws ServiceException If not found
     * @throws Throwable
     */
    public function update(IArtifact $m): void
    {
        $mag = $this->magazineRepository->findById($m->genericObject->id);

        if (!$mag) {
            throw new ServiceException("Magazine not found!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->update($m->genericObject);
            $this->magazineRepository->update($m);
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Delete a Magazine
     *
     * @param string $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void
    {
        $m = $this->magazineRepository->findById($id);

        if (is_null($m)) {
            throw new ServiceException("Magazine not found!");
        }

        $this->magazineRepository->delete($id);
    }

    /**
     * {@inheritDoc}
     * @param array $request
     *
     * @return Magazine
     * @throws RepositoryException
     * @throws ServiceException
     */
    public function fromRequest(array $request): Magazine
    {
        $genericObject = new GenericObject(
            $request["objectId"],
            $request["note"] ?? null,
            $request["url"] ?? null,
            $request["tag"] ?? null
        );

        $publisher = $this->publisherRepository->findById($request["publisherId"]);
        if (!$publisher) {
            throw new ServiceException('Publisher not found');
        }

        return new Magazine(
            genericObject: $genericObject,
            title: $request["title"],
            year: $request["year"],
            magazineNumber: $request["magazineNumber"],
            publisher: $publisher
        );
    }
}
