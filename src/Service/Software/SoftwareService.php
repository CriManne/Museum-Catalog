<?php

declare(strict_types=1);

namespace App\Service\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\GenericObject;
use App\Models\Software\Software;
use App\Repository\Computer\OsRepository;
use App\Repository\Software\SoftwareRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Service\IArtifactService;

class SoftwareService implements IArtifactService
{
    public function __construct(
        protected SoftwareRepository     $softwareRepository,
        protected SoftwareTypeRepository $softwareTypeRepository,
        protected SupportTypeRepository  $supportTypeRepository,
        protected OsRepository           $osRepository
    )
    {
    }

    /**
     * Insert software
     *
     * @param Software $s The software to save
     *
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Software $s): void
    {
        $software = $this->softwareRepository->findFirst(new FetchParams(
            conditions: "title = :title",
            bind: ["title" => $s->title]
        ));

        if ($software) {
            throw new ServiceException("Software title already used!");
        }

        $this->softwareRepository->save($s);
    }

    /**
     * Select by id
     *
     * @param string $id The id to select
     *
     * @return Software The software selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(string $id): Software
    {
        $software = $this->softwareRepository->findById($id);

        if (!$software) {
            throw new ServiceException("Software not found");
        }

        return $software;
    }

    /**
     * Select by key
     *
     * @param string $key The key given
     *
     * @return array Software(s) selected, empty array if no result
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->softwareRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the Software(s)
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->softwareRepository->find();
    }

    /**
     * Update a Software
     *
     * @param Software $s The Software to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Software $s): void
    {
        $soft = $this->softwareRepository->findById($s->genericObject->id);

        if (!$soft) {
            throw new ServiceException("Software not found!");
        }

        $this->softwareRepository->update($s);
    }

    /**
     * Delete a Software
     *
     * @param string $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void
    {
        $s = $this->softwareRepository->findById($id);

        if (!$s) {
            throw new ServiceException("Software not found!");
        }

        $this->softwareRepository->delete($id);
    }


    /**
     * {@inheritDoc}
     *
     * @param array $request
     *
     * @return Software
     * @throws RepositoryException
     * @throws ServiceException
     */
    public function fromRequest(array $request): Software
    {
        $genericObject = new GenericObject(
            $request["objectId"],
            $request["note"] ?? null,
            $request["url"] ?? null,
            $request["tag"] ?? null
        );

        $os = $this->osRepository->findById($request["osId"]);

        if (!$os) {
            throw new ServiceException("Os not found!");
        }

        $softwareType = $this->softwareTypeRepository->findById($request["softwareTypeId"]);

        if (!$softwareType) {
            throw new ServiceException("Software type not found!");
        }

        $supportType = $this->supportTypeRepository->findById($request["supportTypeId"]);

        if(!$supportType) {
            throw new ServiceException("Support type not found!");
        }

        return new Software(
            genericObject: $genericObject,
            title: $request["title"],
            os: $os,
            softwareType: $softwareType,
            supportType: $supportType
        );
    }
}
