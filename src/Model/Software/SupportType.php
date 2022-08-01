<?php

    declare(strict_types=1);

    namespace App\Model\Software;

    use App\Model\GenericObject;

    class SupportType{

        public ?int $ID;
        public string $Name;

        public function __construct(
            ?int $ID,
            string $Name
        ){  
            $this->ID = $ID;
            $this->Name = $Name;
        }

    }