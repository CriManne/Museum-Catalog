<?php

declare(strict_types=1);

namespace App\Model\Magazine;

use App\Model\Book\Publisher;
use App\Model\GenericObject;

class Magazine extends GenericObject
{
    public function __construct(
        public string    $objectId,
        public string    $title,
        public int       $year,
        public int       $magazineNumber,
        public Publisher $publisher,
        string           $note = null,
        string           $url = null,
        string           $tag = null,
    )
    {
        parent::__construct($objectId, $note, $url, $tag);
    }
}
