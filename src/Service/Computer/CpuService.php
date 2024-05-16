<?php

declare(strict_types=1);

namespace App\Service\Computer;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use AbstractRepo\Interfaces\IModel;
use App\Exception\ServiceException;
use App\Models\Computer\Cpu;
use App\Repository\Computer\CpuRepository;
use App\Service\IComponentService;

class CpuService implements IComponentService
{
    public CpuRepository $cpuRepository;

    public function __construct(CpuRepository $cpuRepository)
    {
        $this->cpuRepository = $cpuRepository;
    }

    /**
     * Insert a cpu
     * @param Cpu $c The cpu to save
     * @throws ServiceException If the cpu name is already saved
     * @throws AbstractRepositoryException
     */
    public function save(Cpu $c): void
    {
        $cpu = $this->cpuRepository->findFirst(
            new FetchParams(
                conditions: "modelName = :modelName AND speed = :speed",
                bind: [
                    "modelName" => $c->modelName,
                    "speed" => $c->speed
                ]
            )
        );
        if ($cpu && $cpu->speed == $c->speed) {
            throw new ServiceException("Cpu name and speed already used!");
        }

        $this->cpuRepository->save($c);
    }

    /**
     * Select cpu by id
     * @param int $id The id to select
     * @return Cpu|IModel The cpu selected
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function findById(int $id): Cpu|IModel
    {
        $cpu = $this->cpuRepository->findById($id);
        if (is_null($cpu)) {
            throw new ServiceException("Cpu not found");
        }

        return $cpu;
    }

    /**
     * Select cpu by key
     * @param string $key The key to search
     * @return Cpu[]|IModel[] The cpus selected
     * @throws AbstractRepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->cpuRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return Cpu[]|IModel[] All the cpus
     * @throws AbstractRepositoryException
     */
    public function find(): array
    {
        return $this->cpuRepository->find();
    }

    /**
     * Update a cpu
     * @param Cpu $c The cpu to update
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function update(Cpu $c): void
    {
        $cpu = $this->cpuRepository->findById($c->id);
        if (is_null($cpu)) {
            throw new ServiceException("Cpu not found!");
        }

        $this->cpuRepository->update($c);
    }

    /**
     * Delete a cpu
     * @param int $id The id to delete
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function delete(int $id): void
    {
        $c = $this->cpuRepository->findById($id);
        if (is_null($c)) {
            throw new ServiceException("Cpu not found!");
        }

        $this->cpuRepository->delete($id);
    }
}
