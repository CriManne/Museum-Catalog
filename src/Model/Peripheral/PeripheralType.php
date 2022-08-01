<?php

    declare(strict_types=1);

    namespace App\Model\Peripheral;


    class PeripheralType{
        
        public int $PeripheralTypeID;
        public string $PeripheralTypeName;

        public function __construct(
            int $PeripheralTypeID,
            string $PeripheralTypeName
        ){            
            $this->PeripheralTypeID = $PeripheralTypeID;
            $this->PeripheralTypeName = $PeripheralTypeName;
        }
    }
?>