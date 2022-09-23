<?php

declare(strict_types=1);

namespace App\Model;

class GenericObject {

    public string $ObjectID;
    public ?string $Note;
    public ?string $Url;
    public ?string $Tag;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null
    ) {
        $this->ObjectID = $ObjectID;
        $this->Note = $Note;
        $this->Url = $Url;
        $this->Tag = $Tag;
    }
}
