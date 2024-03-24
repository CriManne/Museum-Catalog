<?php

declare(strict_types=1);

namespace App\Service\Computer;

use AbstractRepo\DataModels\FetchParams;
use App\Exception\ServiceException;
use App\Model\Computer\Cpu;
use App\Repository\Computer\CpuRepository;
use App\Exception\RepositoryException;

class CpuService {

    public CpuRepository $cpuRepository;

    public function __construct(CpuRepository $cpuRepository) {
        $this->cpuRepository = $cpuRepository;
    }

    /**
     * Insert a cpu
     * @param Cpu $c The cpu to save
     * @throws ServiceException If the cpu name is already saveed
     * @throws RepositoryException If the save fails
     */
    public function save(Cpu $c): void {
        $cpu = $this->cpuRepository->findFirst(
            new FetchParams(
                conditions: "modelName = :modelName AND speed = :speed",
                bind: [
                    "modelName" => $c->modelName,
                    "speed" => $c->speed
                ]
            )
        );
        if ($cpu && $cpu->speed == $c->speed)
            throw new ServiceException("Cpu name and speed already used!");

        $this->cpuRepository->save($c);
    }

    /**
     * Select cpu by id
     * @param int $id The id to select
     * @return Cpu The cpu selected
     * @throws ServiceException If not found
     */
    public function findById(int $id): Cpu {
        $cpu = $this->cpuRepository->findById($id);
        if (is_null($cpu)) {
            throw new ServiceException("Cpu not found");
        }

        return $cpu;
    }

    /**
     * Select cpu by name
     * @param string $name The cpu name to select
     * @return Cpu The cpu selected 
     * @throws ServiceException If not found
     */
    public function findByName(string $name): Cpu {
        $cpu = $this->cpuRepository->findByName($name);
        if (is_null($cpu)) {
            throw new ServiceException("Cpu not found");
        }

        return $cpu;
    }

    /**
     * Select cpu by key
     * @param string $key The key to search
     * @return array The cpus selected 
     */
    public function findByQuery(string $key): array {
        return $this->cpuRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the cpus
     */
    public function find(): array {
        return $this->cpuRepository->find();
    }

    /**
     * Update a cpu
     * @param Cpu $c The cpu to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Cpu $c): void {
        $cpu = $this->cpuRepository->findById($c->id);
        if (is_null($cpu)) {
            throw new ServiceException("Cpu not found!");
        }

        $this->cpuRepository->update($c);
    }

    /**
     * Delete a cpu
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $c = $this->cpuRepository->findById($id);
        if (is_null($c)) {
            throw new ServiceException("Cpu not found!");
        }

        $this->cpuRepository->delete($id);
    }
}
