<?php
    declare(strict_types=1);

    namespace App\Service\Computer;

    use App\Exception\ServiceException;
    use App\Model\Computer\Os;
    use App\Repository\Computer\OsRepository;

    class OsService{

        public OsRepository $osRepository;

        public function __construct(OsRepository $osRepository)
        {
            $this->osRepository = $osRepository;
        }

        public function insert(Os $s):void{
            if($this->osRepository->selectByName($s->Name) != null)
                throw new ServiceException("Os name already used!");

            $this->osRepository->insert($s);
        }

        public function selectById(int $id): Os{
            $os = $this->osRepository->selectById($id); 
            if($os == null) throw new ServiceException("Os not found");

            return $os;
        }

        public function selectByName(string $name): Os{
            $os = $this->osRepository->selectByName($name);
            if($os == null) throw new ServiceException("Os not found");

            return $os;
        }

        public function update(Os $s):void{
            if($this->osRepository->selectById($s->OsID) == null)
                throw new ServiceException("Os not found!");

            $this->osRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->osRepository->selectById($id) == null)
                throw new ServiceException("Os not found!");

            $this->osRepository->delete($id);
        }
    }

?>