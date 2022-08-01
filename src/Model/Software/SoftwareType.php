<?php

    declare(strict_types=1);

    namespace App\Model\Software;

    class SoftwareType{

        public int $SoftwareTypeID;
        public string $SoftwareTypeName;
        public string $Erased;

        public function __construct(
            int $SoftwareTypeID,
            string $SoftwareTypeName,
            string $Erased = null
        ){            
            $this->SoftwareTypeID = $SoftwareTypeID;
            $this->SoftwareTypeName = $SoftwareTypeName;
            $this->Erased = $Erased;
        }

    }