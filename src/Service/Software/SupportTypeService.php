<?php
    declare(strict_types=1);

    namespace App\Service\Software;

    use App\Exception\ServiceException;
    use App\Model\Software\SupportType;
    use App\Repository\Software\SupportTypeRepository;

    class SupportTypeService{

        public SupportTypeRepository $supportTypeRepository;

        public function __construct(SupportTypeRepository $supportTypeRepository)
        {
            $this->supportTypeRepository = $supportTypeRepository;
        }

        public function insert(SupportType $s):void{
            if($this->supportTypeRepository->selectByName($s->Name) != null)
                throw new ServiceException("Support Type name already used!");

            $this->supportTypeRepository->insert($s);
        }

        public function selectById(int $id): SupportType{
            $supportType = $this->supportTypeRepository->selectById($id); 
            if($supportType == null) throw new ServiceException("Support Type not found");

            return $supportType;
        }

        public function selectByName(string $name): SupportType{
            $supportType = $this->supportTypeRepository->selectByName($name);
            if($supportType == null) throw new ServiceException("Support Type not found");

            return $supportType;
        }

        public function update(SupportType $s):void{
            if($this->supportTypeRepository->selectById($s->ID) == null)
                throw new ServiceException("Support Type not found!");

            $this->supportTypeRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->supportTypeRepository->selectById($id) == null)
                throw new ServiceException("Support Type not found!");

            $this->supportTypeRepository->delete($id);
        }
    }

?>