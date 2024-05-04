<?php

declare(strict_types=1);

namespace App\Util;

use ReflectionClass;
use ReflectionException;

class ORM {

    /**
     * Return a new instance of the class passed as string with the params passed as array
     * @param string $className The class name of the new instance
     * @param array $obj The params to fill the class with
     * @return object The new instance of the object
     * @throws ReflectionException
     */
    public static function getNewInstance(string $className, array $obj): object {
        $reflectionClass = new ReflectionClass($className);
        return $reflectionClass->newInstanceArgs($obj);
    }
}
