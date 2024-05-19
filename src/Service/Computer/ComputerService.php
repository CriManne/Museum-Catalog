<?php

declare(strict_types=1);

namespace App\Service\Computer;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\Computer\Computer;
use App\Models\Computer\Cpu;
use App\Models\Computer\Os;
use App\Models\Computer\Ram;
use App\Models\GenericObject;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;
use App\Service\IArtifactService;

class ComputerService implements IArtifactService
{
    public function __construct(
        protected ComputerRepository $computerRepository,
        protected CpuRepository      $cpuRepository,
        protected OsRepository       $osRepository,
        protected RamRepository      $ramRepository
    )
    {
    }

    /**
     * Insert computer
     *
     * @param Computer $c The computer to save
     *
     * @throws ServiceException If the ModelName is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Computer $c): void
    {
        $computer = $this->computerRepository->findFirst(new FetchParams(
            conditions: "modelName = :modelName",
            bind: ["modelName" => $c->modelName]
        ));

        if ($computer) {
            throw new ServiceException("Computer model name already used!");
        }

        $this->computerRepository->save($c);
    }

    /**
     * Select by id
     *
     * @param string $id The id to select
     *
     * @return Computer The computer selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(string $id): Computer
    {
        $computer = $this->computerRepository->findById($id);
        if (!$computer) {
            throw new ServiceException("Computer not found");
        }

        return $computer;
    }

    /**
     * Select by key
     *
     * @param string $key The key given
     *
     * @return array The array of computers, empty if no result
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->computerRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the computers
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->computerRepository->find();
    }

    /**
     * Update a Computer
     *
     * @param Computer $c The Computer to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Computer $c): void
    {
        $comp = $this->computerRepository->findById($c->genericObject->id);
        if (!$comp) {
            throw new ServiceException("Computer not found!");
        }

        $this->computerRepository->update($c);
    }

    /**
     * Delete a Computer
     *
     * @param string $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void
    {
        $c = $this->computerRepository->findById($id);
        if (!$c) {
            throw new ServiceException("Computer not found!");
        }

        $this->computerRepository->delete($id);
    }

    /**
     * {@inheritDoc}
     * @param array $request
     *
     * @return Computer
     * @throws RepositoryException
     * @throws ServiceException
     */
    public function fromRequest(array $request): Computer
    {
        $genericObject = new GenericObject(
            $request["objectId"],
            $request["note"] ?? null,
            $request["url"] ?? null,
            $request["tag"] ?? null
        );

        $cpu = $this->cpuRepository->findById($request["cpuId"]);
        if (!$cpu) {
            throw new ServiceException("Cpu not found!");
        }

        $os = null;
        if (isset($request["osId"])) {
            $os = $this->osRepository->findById($request["osId"]);
        }

        $ram = $this->ramRepository->findById($request["ramId"]);
        if (!$ram) {
            throw new ServiceException("Ram not found");
        }

        return new Computer(
            genericObject: $genericObject,
            modelName: $request["modelName"],
            year: $request["year"],
            hddSize: $request["hddSize"],
            cpu: $cpu,
            ram: $ram,
            os: $os
        );
    }
}
