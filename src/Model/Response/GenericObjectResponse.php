<?php

declare(strict_types=1);

namespace App\Model\Response;

use App\Model\GenericObject;

class GenericObjectResponse extends GenericObject {

    public string $ObjectID;
    public string $Title;
    public array $Descriptors; 
    public string $Category;

    public function __construct(
        string $ObjectID,
        string $Title,
        array $Descriptors,
        string $Category,
        string $Note = null,
        string $Url = null,
        string $Tag = null
    ) {
        //TODO: FIX ACTIVE FIELD
        parent::__construct($ObjectID,$Note,$Url,$Tag,"");        
        $this->Title = $Title;
        $this->Descriptors = $Descriptors;      
        $this->Category = $Category;  
    }
}
