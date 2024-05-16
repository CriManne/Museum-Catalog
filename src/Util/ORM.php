<?php

declare(strict_types=1);

namespace App\Util;

use AbstractRepo\Repository\AbstractRepository;
use ReflectionClass;
use ReflectionException;

class ORM
{
    public const string MODEL_PATH_PREFIX = 'App\\Models\\';

    /**
     * Return a new instance of the class passed as string with the params passed as array
     *
     * @param string $className The class name of the new instance
     * @param array  $obj       The params to fill the class with
     *
     * @return object The new instance of the object
     * @throws ReflectionException
     */
    public static function getNewInstance(string $className, array $obj): object
    {
        $reflectionClass = new ReflectionClass($className);
        return $reflectionClass->newInstanceArgs($obj);
    }

    /**
     * TODO: Check if can be replaced by {@see AbstractRepository::getMappedObject()}
     * Returns the new instance of the model
     *
     * @param string $category
     * @param string $name
     * @param array  $params
     *
     * @return object
     * @throws ReflectionException
     */
    public static function getNewModelInstance(
        string $category,
        string $name,
        array  $params
    ): object
    {
        return self::getNewInstance(
            self::MODEL_PATH_PREFIX . $category . '\\' . $name,
            $params
        );
    }
}
