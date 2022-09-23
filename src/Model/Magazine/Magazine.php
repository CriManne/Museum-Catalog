<?php

declare(strict_types=1);

namespace App\Model\Magazine;

use App\Model\Book\Publisher;
use App\Model\GenericObject;

class Magazine extends GenericObject {

    public string $Title;
    public int $MagazineNumber;
    public Publisher $Publisher;
    public int $Year;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $Title,
        int $Year,
        int $MagazineNumber,
        Publisher $Publisher
    ) {
        parent::__construct($ObjectID, $Note, $Url, $Tag);
        $this->Title = $Title;
        $this->Publisher = $Publisher;
        $this->Year = $Year;
        $this->MagazineNumber = $MagazineNumber;
    }
}
