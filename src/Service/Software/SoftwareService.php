<?php

declare(strict_types=1);

namespace App\Service\Software;

use _PHPStan_7961f7ae1\Nette\NotImplementedException;
use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Interfaces\IModel;
use App\Exception\ServiceException;
use App\Models\Software\Software;
use App\Repository\Software\SoftwareRepository;
use App\Exception\RepositoryException;
use App\Service\IArtifactService;

class SoftwareService implements IArtifactService
{
    public SoftwareRepository $softwareRepository;

    public function __construct(SoftwareRepository $softwareRepository)
    {
        $this->softwareRepository = $softwareRepository;
    }

    /**
     * Insert software
     * @param Software $s The software to save
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Software $s): void
    {
        $software = $this->softwareRepository->findFirst(new FetchParams(
            conditions: "title = :title",
            bind: ["title" => $s->title]
        ));

        if ($software)
            throw new ServiceException("Software title already used!");

        $this->softwareRepository->save($s);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Software The software selected
     * @throws ServiceException If not found
     */
    public function findById(string $id): Software
    {
        $software = $this->softwareRepository->findById($id);
        if (is_null($software)) {
            throw new ServiceException("Software not found");
        }

        return $software;
    }

    /**
     * Select by title
     * @param string $title The title to select
     * @return Software The software selected
     * @throws ServiceException If not found
     */
    public function findByTitle(string $title): Software
    {
        $software = $this->softwareRepository->findFirst(new FetchParams(
            conditions: "title = :title",
            bind: ["title" => $title]
        ));

        if (is_null($software)) {
            throw new ServiceException("Software not found");
        }

        return $software;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array Software(s) selected, empty array if no result
     */
    public function findByQuery(string $key): array
    {
        return $this->softwareRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the Software(s)
     */
    public function find(): array
    {
        return $this->softwareRepository->find();
    }

    /**
     * Update a Software
     * @param Software $s The Software to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Software $s): void
    {
        $soft = $this->softwareRepository->findById($s->genericObject->id);

        if (is_null($soft)) {
            throw new ServiceException("Software not found!");
        }

        $this->softwareRepository->update($s);
    }

    /**
     * Delete a Software
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void
    {
        $s = $this->softwareRepository->findById($id);
        if (is_null($s)) {
            throw new ServiceException("Software not found!");
        }

        $this->softwareRepository->delete($id);
    }


    /**
     * @inheritDoc
     */
    public function fromRequest(array $request): IModel
    {
        throw new NotImplementedException();
    }
}
