<?php
    declare(strict_types=1);

    namespace App\Service\Software;

    use App\Exception\ServiceException;
    use App\Model\Software\SoftwareType;
    use App\Repository\Software\SoftwareTypeRepository;

    class SoftwareTypeService{

        public SoftwareTypeRepository $softwareTypeRepository;

        public function __construct(SoftwareTypeRepository $softwareTypeRepository)
        {
            $this->softwareTypeRepository = $softwareTypeRepository;
        }

        public function insert(SoftwareType $s):void{
            if($this->softwareTypeRepository->selectByName($s->Name) != null)
                throw new ServiceException("Software Type name already used!");

            $this->softwareTypeRepository->insert($s);
        }

        public function selectById(int $id): SoftwareType{
            $softwareType = $this->softwareTypeRepository->selectById($id); 
            if($softwareType == null) throw new ServiceException("Software Type not found");

            return $softwareType;
        }

        public function selectByName(string $name): SoftwareType{
            $softwareType = $this->softwareTypeRepository->selectByName($name);
            if($softwareType == null) throw new ServiceException("Software Type not found");

            return $softwareType;
        }

        public function update(SoftwareType $s):void{
            if($this->softwareTypeRepository->selectById($s->SoftwareTypeID) == null)
                throw new ServiceException("Software Type not found!");

            $this->softwareTypeRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->softwareTypeRepository->selectById($id) == null)
                throw new ServiceException("Software Type not found!");

            $this->softwareTypeRepository->delete($id);
        }
    }

?>