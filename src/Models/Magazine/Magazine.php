<?php

    declare(strict_types=1);

    namespace Models\Magazine;

    use Models\GenericObject;

    class Magazine{

        public GenericObject $genericObject;
        public string $Title;
        public string $publisher;
        public int $Year;
        public int $MagazineNumber;

        public function __construct(
            GenericObject $genericObject,
            string $Title,
            string $publisher,
            int $Year,
            int $MagazineNumber
        ){
            $this->genericObject = $genericObject;
            $this->Title = $Title;
            $this->publisher = $publisher;
            $this->Year = $Year;
            $this->MagazineNumber = $MagazineNumber;
        }

    }
?>