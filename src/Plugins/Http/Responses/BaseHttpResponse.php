<?php

namespace App\Plugins\Http\Responses;

abstract readonly class BaseHttpResponse implements IHttpResponse
{
    public function __construct(
        private int    $code,
        private string $text
    )
    {
    }

    /**
     * Returns the http response code
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Returns the http response text
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}