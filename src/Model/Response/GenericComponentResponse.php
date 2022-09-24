<?php

declare(strict_types=1);

namespace App\Model\Response;

class GenericComponentResponse {

    public int $Id;
    public string $Name;
    public string $Category;

    public function __construct(
        int $Id,
        string $Name,
        string $Category
    ) {        
        $this->Id = $Id;
        $this->Name = $Name;      
        $this->Category = $Category;  
    }
}
