<?php

namespace App\Plugins\Http\Responses;

readonly class BadRequest extends BaseHttpResponse
{
    public const int    CODE = 400;
    public const string TEXT = 'BadRequest';

    public function __construct(?string $text = null)
    {
        parent::__construct(self::CODE, $text ??self::TEXT);
    }
}