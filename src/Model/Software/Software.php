<?php

    declare(strict_types=1);

    namespace App\Model\Software;

    use App\Model\GenericObject;

    class Software extends GenericObject{

        public string $Title;
        public string $os;
        public string $SoftwareType;
        public string $SupportType;

        public function __construct(
            string $ObjectID,
            string $Note = null,
            string $Url = null,
            string $Tag = null,
            string $Active,
            string $Erased = null,
            string $Title,
            string $os,
            string $SoftwareType,
            string $SupportType
        ){
            parent::__construct($ObjectID,$Note,$Url,$Tag,$Active,$Erased);
            $this->Title = $Title;
            $this->os = $os;
            $this->SoftwareType = $SoftwareType;
            $this->SupportType = $SupportType;
        }

    }