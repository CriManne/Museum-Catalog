<?php

declare(strict_types=1);

namespace App\Model\Computer;

use App\Model\GenericObject;

class Computer extends GenericObject
{
    public function __construct(
        public string  $objectId,
        public string  $modelName,
        public int     $year,
        public ?string $hddSize,
        public Cpu     $cpu,
        public Ram     $ram,
        public ?Os     $os,
        string         $note = null,
        string         $url = null,
        string         $tag = null
    )
    {
        parent::__construct($objectId, $note, $url, $tag);
    }
}
