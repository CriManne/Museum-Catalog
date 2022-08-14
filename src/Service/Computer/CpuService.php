<?php
    declare(strict_types=1);

    namespace App\Service\Computer;

    use App\Exception\ServiceException;
    use App\Model\Computer\Cpu;
    use App\Repository\Computer\CpuRepository;

    class CpuService{

        public CpuRepository $cpuRepository;

        public function __construct(CpuRepository $cpuRepository)
        {
            $this->cpuRepository = $cpuRepository;
        }

        public function insert(Cpu $s):void{
            $cpu = $this->cpuRepository->selectById($s->CpuID);
            if($cpu->ModelName == $s->ModelName && $cpu->Speed == $s->Speed)
                throw new ServiceException("Cpu name and speed already used!");

            $this->cpuRepository->insert($s);
        }

        public function selectById(int $id): Cpu{
            $cpu = $this->cpuRepository->selectById($id); 
            if($cpu == null) throw new ServiceException("Cpu not found");

            return $cpu;
        }

        public function selectByName(string $name): Cpu{
            $cpu = $this->cpuRepository->selectByName($name);
            if($cpu == null) throw new ServiceException("Cpu not found");

            return $cpu;
        }

        public function update(Cpu $s):void{
            if($this->cpuRepository->selectById($s->CpuID) == null)
                throw new ServiceException("Cpu not found!");

            $this->cpuRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->cpuRepository->selectById($id) == null)
                throw new ServiceException("Cpu not found!");

            $this->cpuRepository->delete($id);
        }
    }

?>