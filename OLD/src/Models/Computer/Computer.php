<?php
    declare(strict_types=1);

    namespace Models\Computer;

    use Models\GenericObject;
    
    class Computer extends GenericObject{        
        
        public string $ModelName;
        public int $Year;
        public Cpu $cpu;
        public Ram $ram;
        public string $HddSize;
        public string $os;
        
        public function __construct(
            string $ObjectID,
            string $Note = null,
            string $Url = null,
            string $Tag = null,
            string $Active,
            string $Erased = null,
            string $ModelName,
            int $Year,
            Cpu $cpu,
            Ram $ram,
            string $HddSize,
            string $os
        ){
            parent::__construct($ObjectID,$Note,$Url,$Tag,$Active,$Erased);
            $this->ModelName = $ModelName;
            $this->Year = $Year;
            $this->cpu = $cpu;
            $this->ram = $ram;
            $this->HddSize = $HddSize;
            $this->os = $os;
        }

    }
?>