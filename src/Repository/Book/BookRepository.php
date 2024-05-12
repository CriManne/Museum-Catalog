<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\Book\Book;
use App\Model\Book\Publisher;
use App\Model\GenericObject;
use App\Util\ORM;
use ReflectionException;

class BookRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Book::class;
    }

    /**
     * @param array $rawBook
     *
     * @return Book
     * @throws ReflectionException
     */
    function returnMappedObject(array $rawBook): Book
    {
        return new Book(
            ORM::getNewInstance(GenericObject::class, $rawBook["genericObject"]),
            $rawBook["title"],
            ORM::getNewInstance(Publisher::class, $rawBook["publisher"]),
            intval($rawBook["year"]),
            $rawBook["authors"],
            $rawBook["isbn"] ?? null,
            isset($rawBook["pages"]) ? intval($rawBook["pages"]) : null,
        );
    }
}
