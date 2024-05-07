<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\Computer\Os;
use App\Model\GenericObject;
use App\Model\Software\Software;
use App\Model\Software\SoftwareType;
use App\Model\Software\SupportType;
use App\Util\ORM;
use ReflectionException;

class SoftwareRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Software::class;
    }

    /**
     * Return a new instance of Software from an array
     * @param array $rawSoftware
     * @return Software The new instance of software with the fk filled with the result of selects
     * @throws ReflectionException
     */
    function returnMappedObject(array $rawSoftware): Software
    {
        return new Software(
            ORM::getNewInstance(GenericObject::class, $rawSoftware["genericObject"]),
            $rawSoftware["title"],
            ORM::getNewInstance(Os::class, $rawSoftware["os"]),
            ORM::getNewInstance(SoftwareType::class, $rawSoftware["softwareType"]),
            ORM::getNewInstance(SupportType::class, $rawSoftware["supportType"])
        );
    }
}
