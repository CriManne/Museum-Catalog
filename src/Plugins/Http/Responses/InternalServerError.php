<?php

namespace App\Plugins\Http\Responses;

readonly class InternalServerError extends BaseHttpResponse
{
    public const int    CODE = 500;
    public const string TEXT = 'Internal server error';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}