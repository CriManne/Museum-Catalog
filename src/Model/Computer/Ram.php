<?php

    declare(strict_types=1);

    namespace App\Model\Computer;

    class Ram {
        public int $RamID;
        public string $ModelName;        
        public string $Size;        
        
        public function __construct(
            int $RamID,
            string $ModelName,            
            string $Size,            
        ){
            $this->RamID = $RamID;
            $this->ModelName = $ModelName;            
            $this->Size = $Size;            
        }
    }

?>