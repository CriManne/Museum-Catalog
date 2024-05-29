<?php

namespace App\Plugins\Http\Responses;

/**
 * Interface for the http responses
 */
interface IHttpResponse
{
    public function getCode(): int;
    public function getText(): string;
}