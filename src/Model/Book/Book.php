<?php

declare(strict_types=1);

namespace App\Model\Book;

use App\Model\GenericObject;

class Book extends GenericObject
{
    public function __construct(
        public string    $objectId,
        public string    $title,
        public Publisher $publisher,
        public int       $year,
        public array     $authors,
        string           $note = null,
        string           $url = null,
        string           $tag = null,
        public ?string   $isbn = null,
        public ?int      $Pages = null,
    )
    {
        parent::__construct($objectId, $note, $url, $tag);
    }
}
