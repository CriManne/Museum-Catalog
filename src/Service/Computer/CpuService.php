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
     * @return Cpu The cpu inserted
     * @throws ServiceException If the cpu name is already inserted
     * @throws RepositoryException If the insert fails
     */
    public function insert(Cpu $c): Cpu {
        $cpu = $this->cpuRepository->selectById($c->CpuID);
        if ($cpu->ModelName == $c->ModelName && $cpu->Speed == $c->Speed)
            throw new ServiceException("Cpu name and speed already used!");

        return $this->cpuRepository->insert($c);
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
     * Update a cpu
     * @param Cpu $c The cpu to update
     * @return Cpu The cpu updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Cpu $c): Cpu {
        if ($this->cpuRepository->selectById($c->CpuID) == null)
            throw new ServiceException("Cpu not found!");

        return $this->cpuRepository->update($c);
    }

    /**
     * Delete a cpu
     * @param int $id The id to delete
     * @return Cpu The cpu deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): Cpu {
        $c = $this->cpuRepository->selectById($id);
        if ($c == null)
            throw new ServiceException("Cpu not found!");

        $this->cpuRepository->delete($id);
        return $c;
    }
}
