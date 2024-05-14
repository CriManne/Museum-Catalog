<?php

namespace App\Plugins\Http\Responses;

readonly class NoContent extends BaseHttpResponse
{
    public const int    CODE = 204;
    public const string TEXT = 'No content';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}