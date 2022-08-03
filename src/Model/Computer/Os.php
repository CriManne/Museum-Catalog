<?php

    declare(strict_types=1);

    namespace App\Model\Computer;

    class Os {
        public ?int $ID;
        public string $Name;    
        public ?string $Erased;           
        
        public function __construct(
            ?int $ID,
            string $Name,
            ?string $Erased = null      
        ){
            $this->ID = $ID;
            $this->Name = $Name;        
            $this->Erased = $Erased;
        }
    }

?>