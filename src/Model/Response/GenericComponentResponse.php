<?php

declare(strict_types=1);

namespace App\Model\Response;

class GenericComponentResponse {

    public int $ID;
    public string $Name;
    public string $Category;

    public function __construct(
        int $ID,
        string $Name,
        string $Category
    ) {        
        $this->ID = $ID;
        $this->Name = $Name;      
        $this->Category = $Category;  
    }
}
