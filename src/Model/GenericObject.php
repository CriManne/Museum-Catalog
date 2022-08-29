<?php

declare(strict_types=1);

namespace App\Model;

class GenericObject {

    public string $ObjectID;
    public ?string $Note;
    public ?string $Url;
    public ?string $Tag;
    public string $Active;
    public ?string $Erased;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $Active,
        string $Erased = null
    ) {
        $this->ObjectID = $ObjectID;
        $this->Note = $Note;
        $this->Url = $Url;
        $this->Tag = $Tag;
        $this->Active = $Active;
        $this->Erased = $Erased;
    }
}
