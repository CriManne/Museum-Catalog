<?php

    declare(strict_types=1);

    namespace App\Model\Peripheral;


    class PeripheralType{
        
        public int $PeripheralTypeID;
        public string $PeripheralTypeName;
        public string $Erased;

        public function __construct(
            int $PeripheralTypeID,
            string $PeripheralTypeName,
            string $Erased = null
        ){            
            $this->PeripheralTypeID = $PeripheralTypeID;
            $this->PeripheralTypeName = $PeripheralTypeName;
            $this->Erased = $Erased;
        }
    }
?>