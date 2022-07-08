<?php

    declare(strict_types=1);

    namespace Models\Software;

    use Models\GenericObject;

    class Software{

        public GenericObject $genericObject;
        public string $Title;
        public string $os;
        public string $SoftwareType;
        public string $SupportType;

        public function __construct(
            GenericObject $genericObject,
            string $Title,
            string $os,
            string $SoftwareType,
            string $SupportType
        ){
            $this->genericObject = $genericObject;
            $this->Title = $Title;
            $this->os = $os;
            $this->SoftwareType = $SoftwareType;
            $this->SupportType = $SupportType;
        }

    }