<?php

    declare(strict_types=1);

    namespace App\Model\Software;

    class SoftwareType{

        public int $SoftwareTypeID;
        public string $SoftwareTypeName;

        public function __construct(
            int $SoftwareTypeID,
            string $SoftwareTypeName
        ){            
            $this->SoftwareTypeID = $SoftwareTypeID;
            $this->SoftwareTypeName = $SoftwareTypeName;
        }

    }