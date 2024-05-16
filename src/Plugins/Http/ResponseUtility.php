<?php

namespace App\Plugins\Http;

use App\Plugins\Http\Responses\IHttpResponse;

class ResponseUtility
{
    /**
     * Return a json response
     *
     * @param IHttpResponse|string $response
     *
     * @return string The response json
     */
    public static function getJson(IHttpResponse|string $response): string
    {
        if (is_string($response)) {
            return json_encode(["message" => $response]);
        }

        return json_encode(["message" => $response->getText()]);
    }
}