<?php

declare(strict_types=1);

namespace App\Model\Response;

class GenericObject {

    public string $ObjectID;
    public string $Title;
    public array $Descriptors;
    public ?string $Note;
    public ?string $Url;
    public ?string $Tag;
    public string $Active;
    public ?string $Erased;

    public function __construct(
        string $ObjectID,
        string $Title,
        array $Descriptors,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $Active,
        string $Erased = null
    ) {
        $this->ObjectID = $ObjectID;
        $this->Title = $Title;
        $this->Descriptors = $Descriptors;
        $this->Note = $Note;
        $this->Url = $Url;
        $this->Tag = $Tag;
        $this->Active = $Active;
        $this->Erased = $Erased;
    }
}
