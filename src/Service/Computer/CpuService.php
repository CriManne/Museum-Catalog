<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Cpu;
use App\Repository\Computer\CpuRepository;

class CpuService {

    public CpuRepository $cpuRepository;

    public function __construct(CpuRepository $cpuRepository) {
        $this->cpuRepository = $cpuRepository;
    }

    /**
     * Insert a cpu
     * @param Cpu $c The cpu to insert
     * @throws ServiceException If the cpu name is already inserted
     * @throws RepositoryException If the insert fails
     */
    public function insert(Cpu $c): void {
        $cpu = $this->cpuRepository->selectByName($c->ModelName);
        if ($cpu && $cpu->Speed == $c->Speed)
            throw new ServiceException("Cpu name and speed already used!");

        $this->cpuRepository->insert($c);
    }

    /**
     * Select cpu by id
     * @param int $id The id to select
     * @return Cpu The cpu selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): Cpu {
        $cpu = $this->cpuRepository->selectById($id);
        if ($cpu == null) throw new ServiceException("Cpu not found");

        return $cpu;
    }

    /**
     * Select cpu by name
     * @param string $name The cpu name to select
     * @return Cpu The cpu selected 
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): Cpu {
        $cpu = $this->cpuRepository->selectByName($name);
        if ($cpu == null) throw new ServiceException("Cpu not found");

        return $cpu;
    }

    /**
     * Select cpu by key
     * @param string $key The key to search
     * @return array The cpus selected 
     */
    public function selectByKey(string $key): array {
        return $this->cpuRepository->selectByKey($key);        
    }

    /**
     * Select all
     * @return array All the cpus
     */
    public function selectAll(): array {
        return $this->cpuRepository->selectAll();
    }

    /**
     * Update a cpu
     * @param Cpu $c The cpu to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Cpu $c): void {
        if ($this->cpuRepository->selectById($c->CpuID) == null)
            throw new ServiceException("Cpu not found!");

        $this->cpuRepository->update($c);
    }

    /**
     * Delete a cpu
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $c = $this->cpuRepository->selectById($id);
        if ($c == null)
            throw new ServiceException("Cpu not found!");

        $this->cpuRepository->delete($id);
    }
}
