<?php

declare(strict_types=1);

namespace App\Service\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\DatabaseException;
use App\Exception\ServiceException;
use App\Models\GenericObject;
use App\Models\IArtifact;
use App\Models\Software\Software;
use App\Plugins\DB\DB;
use App\Repository\Computer\OsRepository;
use App\Repository\GenericObjectRepository;
use App\Repository\Software\SoftwareRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Service\IArtifactService;
use Throwable;

class SoftwareService implements IArtifactService
{
    public function __construct(
        protected GenericObjectRepository $genericObjectRepository,
        protected SoftwareRepository      $softwareRepository,
        protected SoftwareTypeRepository  $softwareTypeRepository,
        protected SupportTypeRepository   $supportTypeRepository,
        protected OsRepository            $osRepository
    )
    {
    }

    /**
     * Insert software
     *
     * @param Software $s The software to save
     *
     * @throws RepositoryException If the save fails
     * @throws ServiceException If the title is already used
     * @throws Throwable
     * @throws DatabaseException
     */
    public function save(IArtifact $s): void
    {
        $software = $this->softwareRepository->findFirst(new FetchParams(
            conditions: "title = :title",
            bind: ["title" => $s->title]
        ));

        if ($software) {
            throw new ServiceException("Software title already used!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->save($s->genericObject);
            $this->softwareRepository->save($s);
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
     * @throws DatabaseException
     * @throws RepositoryException If the update fails
     * @throws ServiceException If not found
     * @throws Throwable
     */
    public function update(IArtifact $s): void
    {
        $soft = $this->softwareRepository->findById($s->genericObject->id);

        if (!$soft) {
            throw new ServiceException("Software not found!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->update($s->genericObject);
            $this->softwareRepository->update($s);
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Delete a Software
     *
     * @param string $id The id to delete
     *
     * @throws DatabaseException
     * @throws RepositoryException If the delete fails
     * @throws ServiceException If not found
     * @throws Throwable
     */
    public function delete(string $id): void
    {
        $s = $this->softwareRepository->findById($id);

        if (!$s) {
            throw new ServiceException("Software not found!");
        }

        DB::begin();
        try {
            $this->softwareRepository->delete($id);
            $this->genericObjectRepository->delete($id);
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
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

        if (!$supportType) {
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
