<?php
    declare(strict_types=1);

    namespace App\Service\Software;

    use App\Exception\ServiceException;
    use App\Model\Software\Software;
    use App\Repository\Software\SoftwareRepository;

    class SoftwareService{

        public SoftwareRepository $softwareRepository;

        public function __construct(SoftwareRepository $softwareRepository)
        {
            $this->softwareRepository = $softwareRepository;
        }

        public function insert(Software $s):void{
            if($this->softwareRepository->selectByTitle($s->Title) != null)
                throw new ServiceException("Software already used!");

            $this->softwareRepository->insert($s);
        }

        public function selectById(string $id): Software{
            $software = $this->softwareRepository->selectById($id); 
            if($software == null) throw new ServiceException("Software not found");

            return $software;
        }

        public function selectByTitle(string $title): Software{
            $software = $this->softwareRepository->selectByTitle($title);
            if($software == null) throw new ServiceException("Software not found");

            return $software;
        }

        public function update(Software $s):void{
            if($this->softwareRepository->selectById($s->ID) == null)
                throw new ServiceException("Software not found!");

            $this->softwareRepository->update($s);
        }

        public function delete(string $id): void{
            if($this->softwareRepository->selectById($id) == null)
                throw new ServiceException("Software not found!");

            $this->softwareRepository->delete($id);
        }
    }

?>