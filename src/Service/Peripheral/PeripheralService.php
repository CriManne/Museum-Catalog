<?php

declare(strict_types=1);

namespace App\Service\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use App\Exception\ServiceException;
use App\Models\GenericObject;
use App\Models\Peripheral\Peripheral;
use App\Models\Peripheral\PeripheralType;
use App\Repository\GenericObjectRepository;
use App\Repository\Peripheral\PeripheralRepository;
use App\Exception\RepositoryException;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Service\IArtifactService;

class PeripheralService implements IArtifactService
{
    public function __construct(
        public GenericObjectRepository $genericObjectRepository,
        public PeripheralRepository $peripheralRepository,
        public PeripheralTypeRepository $peripheralTypeRepository
    )
    {
    }

    /**
     * Insert peripheral
     * @param Peripheral $p The peripheral to save
     * @throws ServiceException If the ModelName is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Peripheral $p): void
    {
        $peripheral = $this->peripheralRepository->findFirst(new FetchParams(
            conditions: "modelName = :modelName",
            bind: ["modelName" => $p->modelName]
        ));

        if ($peripheral){
            throw new ServiceException("Peripheral model name already used!");
        }

        $this->genericObjectRepository->save($p->genericObject);
        $this->peripheralRepository->save($p);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Peripheral The peripheral selected
     * @throws ServiceException If not found
     */
    public function findById(string $id): Peripheral
    {
        $peripheral = $this->peripheralRepository->findById($id);

        if (is_null($peripheral)) {
            throw new ServiceException("Peripheral not found");
        }

        return $peripheral;
    }

    /**
     * Select by ModelName
     * @param string $ModelName The ModelName to select
     * @return Peripheral The peripheral selected
     * @throws ServiceException If not found
     */
    public function findByName(string $modelName): Peripheral
    {
        $peripheral = $this->peripheralRepository->findFirst(new FetchParams(
            conditions: "modelName = :modelName",
            bind: ["modelName" => $modelName]
        ));

        if (is_null($peripheral)) {
            throw new ServiceException("Peripheral not found");
        }

        return $peripheral;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array The pheriperals selected, empty if no result
     */
    public function findByQuery(string $key): array
    {
        return $this->peripheralRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the pheriperals
     */
    public function find(): array
    {
        return $this->peripheralRepository->find();
    }

    /**
     * Update a Peripheral
     * @param Peripheral $p The Peripheral to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Peripheral $p): void
    {
        $per = $this->peripheralRepository->findById($p->genericObject->id);
        if (is_null($per)) {
            throw new ServiceException("Peripheral not found!");
        }

        $this->peripheralRepository->update($p);
    }

    /**
     * Delete a Peripheral
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void
    {
        $p = $this->peripheralRepository->findById($id);
        if (is_null($p)) {
            throw new ServiceException("Peripheral not found!");
        }

        $this->peripheralRepository->delete($id);
    }


    /**
     * @inheritDoc
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

        if(!$peripheralType) {
            throw new ServiceException('peripheral_type_not_found');
        }

        return new Peripheral(
            genericObject: $genericObject,
            modelName: $request["modelName"],
            peripheralType: $peripheralType
        );
    }
}
