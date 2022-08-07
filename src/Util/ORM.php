<?php

    declare(strict_types=1);

    namespace App\Util;

use Reflection;
use ReflectionClass;

    class ORM{

        public static function getNewInstance(string $className,array $obj): object{
            $reflectionClass = new ReflectionClass($className);
            return $reflectionClass->newInstanceArgs($obj);
        }

    }