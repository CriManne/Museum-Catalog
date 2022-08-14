<?php
    declare(strict_types=1);

    namespace App\Service\Peripheral;

    use App\Exception\ServiceException;
    use App\Model\Peripheral\PeripheralType;
    use App\Repository\Peripheral\PeripheralTypeRepository;

    class PeripheralTypeService{

        public PeripheralTypeRepository $peripheralTypeRepository;

        public function __construct(PeripheralTypeRepository $peripheralTypeRepository)
        {
            $this->peripheralTypeRepository = $peripheralTypeRepository;
        }

        public function insert(PeripheralType $s):void{
            if($this->peripheralTypeRepository->selectByName($s->Name) != null)
                throw new ServiceException("PeripheralType name already used!");

            $this->peripheralTypeRepository->insert($s);
        }

        public function selectById(int $id): PeripheralType{
            $peripheralType = $this->peripheralTypeRepository->selectById($id); 
            if($peripheralType == null) throw new ServiceException("PeripheralType not found");

            return $peripheralType;
        }

        public function selectByName(string $name): PeripheralType{
            $peripheralType = $this->peripheralTypeRepository->selectByName($name);
            if($peripheralType == null) throw new ServiceException("PeripheralType not found");

            return $peripheralType;
        }

        public function update(PeripheralType $s):void{
            if($this->peripheralTypeRepository->selectById($s->PeripheralTypeID) == null)
                throw new ServiceException("PeripheralType not found!");

            $this->peripheralTypeRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->peripheralTypeRepository->selectById($id) == null)
                throw new ServiceException("PeripheralType not found!");

            $this->peripheralTypeRepository->delete($id);
        }
    }

?>