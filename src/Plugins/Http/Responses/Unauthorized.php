<?php

namespace App\Plugins\Http\Responses;

readonly class Unauthorized extends BaseHttpResponse
{
    public const int    CODE = 401;
    public const string TEXT = 'Unauthorized';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}