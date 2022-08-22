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

        /**
         * Insert SoftwareType
         * @param SoftwareType $s The SoftwareType to insert
         * @return SoftwareType The SoftwareType inserted
         * @throws ServiceException If the name is already used
         * @throws RepositoryException If the insert fails
         */
        public function insert(SoftwareType $s):SoftwareType{
            if($this->softwareTypeRepository->selectByName($s->Name) != null)
                throw new ServiceException("Software Type name already used!");

            return $this->softwareTypeRepository->insert($s);
        }

        /**
         * Select by id
         * @param int $id The id to select
         * @return SoftwareType The SoftwareType selected
         * @throws ServiceException If not found
         */
        public function selectById(int $id): SoftwareType{
            $softwareType = $this->softwareTypeRepository->selectById($id); 
            if($softwareType == null) throw new ServiceException("Software Type not found");

            return $softwareType;
        }

        /**
         * Select by name
         * @param string $name The name to select
         * @return SoftwareType The SoftwareType selected
         * @throws ServiceException If not found
         */
        public function selectByName(string $name): SoftwareType{
            $softwareType = $this->softwareTypeRepository->selectByName($name);
            if($softwareType == null) throw new ServiceException("Software Type not found");

            return $softwareType;
        }

        /**
         * Update SoftwareType
         * @param SoftwareType $s The SoftwareType to update
         * @return SoftwareType The SoftwareType updated
         * @throws ServiceException If not found
         * @throws RepositoryException If the update fails
         */
        public function update(SoftwareType $s):SoftwareType{
            if($this->softwareTypeRepository->selectById($s->SoftwareTypeID) == null)
                throw new ServiceException("Software Type not found!");

            return $this->softwareTypeRepository->update($s);
        }

        /**
         * Delete SoftwareType
         * @param int $id The id to delete
         * @return SoftwareType The SoftwareType deleted
         * @throws ServiceException If not found
         * @throws RepositoryException If the delete fails
         */
        public function delete(int $id): SoftwareType{
            $s = $this->softwareTypeRepository->selectById($id);
            if($s == null)
                throw new ServiceException("Software Type not found!");

            $this->softwareTypeRepository->delete($id);
            return $s;
        }
    }

?>