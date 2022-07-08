<?php
    declare(strict_types=1);

    namespace Models\Computer;

    use Models\GenericObject;
    
    class Computer{        
        
        public GenericObject $genericObject;
        public string $ModelName;
        public int $Year;
        public Cpu $cpu;
        public Ram $ram;
        public string $HddSize;
        public string $os;
        
        public function __construct(
            GenericObject $genericObject,
            string $ModelName,
            int $Year,
            Cpu $cpu,
            Ram $ram,
            string $HddSize,
            string $os
        ){
            $this->genericObject = $genericObject;
            $this->ModelName = $ModelName;
            $this->Year = $Year;
            $this->cpu = $cpu;
            $this->ram = $ram;
            $this->HddSize = $HddSize;
            $this->os = $os;
        }

    }
?>