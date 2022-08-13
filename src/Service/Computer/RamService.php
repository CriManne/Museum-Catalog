<?php
    declare(strict_types=1);

    namespace App\Service\Computer;

    use App\Exception\ServiceException;
    use App\Model\Computer\Ram;
    use App\Repository\Computer\RamRepository;

    class RamService{

        public RamRepository $ramRepository;

        public function __construct(RamRepository $ramRepository)
        {
            $this->ramRepository = $ramRepository;
        }

        public function insert(Ram $s):void{
            $ram = $this->ramRepository->selectById($s->RamID);
            if($ram->ModelName == $s->ModelName && $ram->Size == $s->Size)
                throw new ServiceException("Ram name and size already used!");

            $this->ramRepository->insert($s);
        }

        public function selectById(int $id): Ram{
            $ram = $this->ramRepository->selectById($id); 
            if($ram == null) throw new ServiceException("Ram not found");

            return $ram;
        }

        public function selectByName(string $name): Ram{
            $ram = $this->ramRepository->selectByName($name);
            if($ram == null) throw new ServiceException("Ram not found");

            return $ram;
        }

        public function update(Ram $s):void{
            if($this->ramRepository->selectById($s->RamID) == null)
                throw new ServiceException("Ram not found!");

            $this->ramRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->ramRepository->selectById($id) == null)
                throw new ServiceException("Ram not found!");

            $this->ramRepository->delete($id);
        }
    }

?>