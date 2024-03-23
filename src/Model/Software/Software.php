<?php

declare(strict_types=1);

namespace App\Model\Software;

use App\Model\Computer\Os;
use App\Model\GenericObject;

class Software extends GenericObject
{
    public function __construct(
        public string       $objectId,
        public string       $title,
        public Os           $os,
        public SoftwareType $softwareType,
        public SupportType  $supportType,
        string              $note = null,
        string              $url = null,
        string              $tag = null,
    )
    {
        parent::__construct($objectId, $note, $url, $tag);
    }
}
