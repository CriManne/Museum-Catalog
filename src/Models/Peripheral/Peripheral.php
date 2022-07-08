<?php

    declare(strict_types=1);

    namespace Models\Peripheral;

    use Models\GenericObject;

    class Peripheral{

        public GenericObject $genericObject;
        public string $ModelName;
        public string $pheripheralType;

        public function __construct(
            GenericObject $genericObject,
            string $ModelName,
            string $pheripheralType
        ){
            $this->genericObject = $genericObject;
            $this->ModelName = $ModelName;
            $this->pheripheralType = $pheripheralType;
        }
    }
?>