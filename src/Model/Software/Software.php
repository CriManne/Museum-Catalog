<?php

declare(strict_types=1);

namespace App\Model\Software;

use App\Model\Computer\Os;
use App\Model\GenericObject;

class Software extends GenericObject {

    public string $Title;
    public Os $os;
    public SoftwareType $SoftwareType;
    public SupportType $SupportType;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $Active,
        string $Erased = null,
        string $Title,
        Os $os,
        SoftwareType $SoftwareType,
        SupportType $SupportType
    ) {
        parent::__construct($ObjectID, $Note, $Url, $Tag, $Active, $Erased);
        $this->Title = $Title;
        $this->os = $os;
        $this->SoftwareType = $SoftwareType;
        $this->SupportType = $SupportType;
    }
}
