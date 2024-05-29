<?php

namespace App\Plugins\Http\Responses;

readonly class NotFound extends BaseHttpResponse
{
    public const int    CODE = 404;
    public const string TEXT = 'Not found';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}