<?php

    declare(strict_types=1);

    namespace Mupin\Model\Computer;

    class Cpu{

        public int $CpuID;
        public string $ModelName;
        public string $Speed;

        public function __construct(
            int $CpuID,
            string $ModelName,
            string $Speed                    
        ){
            $this->CpuID = $CpuID;
            $this->ModelName = $ModelName;
            $this->Speed = $Speed;
        }

    }

?>