<?php

namespace App\Plugins\Http\Responses;

readonly class Created extends BaseHttpResponse
{
    public const int    CODE = 201;
    public const string TEXT = 'Created';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}