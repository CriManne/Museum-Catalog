<?php

namespace App\Plugins\Http\Responses;

readonly class Ok extends BaseHttpResponse
{
    public const int    CODE = 200;
    public const string TEXT = 'Ok';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}