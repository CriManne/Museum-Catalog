<?php

    declare(strict_types=1);

    namespace App\Model\Magazine;

    use App\Model\GenericObject;

    class Magazine extends GenericObject{

        public string $Title;
        public string $publisher;
        public int $Year;
        public int $MagazineNumber;

        public function __construct(
            string $ObjectID,
            string $Note = null,
            string $Url = null,
            string $Tag = null,
            string $Active,
            string $Erased = null,
            string $Title,
            string $publisher,
            int $Year,
            int $MagazineNumber
        ){
            parent::__construct($ObjectID,$Note,$Url,$Tag,$Active,$Erased);
            $this->Title = $Title;
            $this->publisher = $publisher;
            $this->Year = $Year;
            $this->MagazineNumber = $MagazineNumber;
        }

    }
?>