<?php

declare(strict_types=1);

namespace App\Service\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\DatabaseException;
use App\Exception\ServiceException;
use App\Models\GenericObject;
use App\Models\IArtifact;
use App\Models\Peripheral\Peripheral;
use App\Models\Peripheral\PeripheralType;
use App\Plugins\DB\DB;
use App\Repository\GenericObjectRepository;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Service\IArtifactService;
use Throwable;

class PeripheralService implements IArtifactService
{
    public function __construct(
        public GenericObjectRepository  $genericObjectRepository,
        public PeripheralRepository     $peripheralRepository,
        public PeripheralTypeRepository $peripheralTypeRepository
    )
    {
    }

    /**
     * Insert peripheral
     *
     * @param Peripheral $p The peripheral to save
     *
     * @throws DatabaseException
     * @throws RepositoryException If the save fails
     * @throws ServiceException If the ModelName is already used
     * @throws Throwable
     */
    public function save(IArtifact $p): void
    {
        $peripheral = $this->peripheralRepository->findFirst(new FetchParams(
            conditions: "modelName = :modelName",
            bind: ["modelName" => $p->modelName]
        ));

        if ($peripheral) {
            throw new ServiceException("Peripheral model name already used!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->save($p->genericObject);
            $this->peripheralRepository->save($p);
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
     * @return Peripheral The peripheral selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(string $id): Peripheral
    {
        $peripheral = $this->peripheralRepository->findById($id);

        if (!$peripheral) {
            throw new ServiceException("Peripheral not found");
        }

        return $peripheral;
    }

    /**
     * Select by ModelName
     *
     * @param string $modelName
     *
     * @return Peripheral The peripheral selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findByName(string $modelName): Peripheral
    {
        $peripheral = $this->peripheralRepository->findFirst(new FetchParams(
            conditions: "modelName = :modelName",
            bind: ["modelName" => $modelName]
        ));

        if (!$peripheral) {
            throw new ServiceException("Peripheral not found");
        }

        return $peripheral;
    }

    /**
     * Select by key
     *
     * @param string $key The key given
     *
     * @return array The peripherals selected, empty if no result
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->peripheralRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the peripherals
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->peripheralRepository->find();
    }

    /**
     * Update a Peripheral
     *
     * @param Peripheral $p The Peripheral to update
     *
     * @throws DatabaseException
     * @throws RepositoryException If the update fails
     * @throws ServiceException If not found
     * @throws Throwable
     */
    public function update(IArtifact $p): void
    {
        $per = $this->peripheralRepository->findById($p->genericObject->id);
        if (!$per) {
            throw new ServiceException("Peripheral not found!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->update($p->genericObject);
            $this->peripheralRepository->update($p);
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Delete a Peripheral
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
        $p = $this->peripheralRepository->findById($id);
        if (!$p) {
            throw new ServiceException("Peripheral not found!");
        }

        DB::begin();
        try {
            $this->peripheralRepository->delete($id);
            $this->genericObjectRepository->delete($id);
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }


    /**
     * {@inheritDoc}
     * @param array $request
     *
     * @return Peripheral
     * @throws ServiceException
     * @throws RepositoryException
     */
    public function fromRequest(array $request): Peripheral
    {
        $genericObject = new GenericObject(
            $request["objectId"],
            $request["note"] ?? null,
            $request["url"] ?? null,
            $request["tag"] ?? null
        );

        /**
         * @var PeripheralType|null $peripheralType
         */
        $peripheralType = $this->peripheralTypeRepository->findById($request["peripheralTypeId"]);

        if (!$peripheralType) {
            throw new ServiceException('peripheral_type_not_found');
        }

        return new Peripheral(
            genericObject: $genericObject,
            modelName: $request["modelName"],
            peripheralType: $peripheralType
        );
    }
}
