<?php

declare(strict_types=1);

namespace App\DataModels\Response;

use App\Model\GenericObject;

class GenericArtifactResponse extends GenericObject {

    public string $objectId;
    public string $title;
    public array $Descriptors;
    public string $Category;

    public function __construct(
        string $objectId,
        string $title,
        array $Descriptors,
        string $Category,
        string $Note = null,
        string $Url = null,
        string $Tag = null
    ) {
        parent::__construct($objectId, $Note, $Url, $Tag);
        $this->title = $title;
        $this->Descriptors = $Descriptors;
        $this->Category = $Category;
    }
}
