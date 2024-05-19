<?php

namespace App\Plugins\Http\Responses;

readonly class Found extends BaseHttpResponse
{
    public const int    CODE = 302;
    public const string TEXT = 'Found';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}