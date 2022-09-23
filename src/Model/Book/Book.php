<?php

declare(strict_types=1);

namespace App\Model\Book;

use App\Model\GenericObject;
use App\Model\Response\GenericObjectResponse;

class Book extends GenericObject {

    public string $Title;
    public Publisher $Publisher;
    public int $Year;
    public string $ISBN;
    public int $Pages;
    public array $Authors;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $Title,
        Publisher $Publisher,
        int $Year,
        string $ISBN,
        int $Pages,
        array $Authors
    ) {
        parent::__construct($ObjectID, $Note, $Url, $Tag);
        $this->Title = $Title;
        $this->Publisher = $Publisher;
        $this->Year = $Year;
        $this->ISBN = $ISBN;
        $this->Pages = $Pages;
        $this->Authors = $Authors;
    }
}
