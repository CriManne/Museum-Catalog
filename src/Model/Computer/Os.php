<?php

    declare(strict_types=1);

    namespace App\Model\Computer;

    class Os {
        public int $OsID;
        public string $OsName;               
        
        public function __construct(
            int $OsID,
            string $OsName          
        ){
            $this->OsID = $OsID;
            $this->OsName = $OsName;        
        }
    }

?>