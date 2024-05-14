<?php

namespace App\Plugins\Http\Responses;

readonly class Forbidden extends BaseHttpResponse
{
    public const int    CODE = 403;
    public const string TEXT = 'Forbidden';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}