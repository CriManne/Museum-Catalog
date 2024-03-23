<?php

namespace App\DataModels;

readonly class FetchableData
{
    public function __construct(
        public int   $page,
        public int   $itemsPerPage,
        public int   $totalPages,
        public array $data
    )
    {
    }
}